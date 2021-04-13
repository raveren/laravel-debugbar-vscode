<?php

namespace ErlangParasu\DebugbarVscode;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Foundation\Application;

class LaravelDebugbarVscode extends ServiceProvider
{
    /**
     * The Laravel application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * True when booted.
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * @param Application $app
     */
    public function __construct($app = null)
    {
        if (!$app) {
            $app = app(); // Fallback when $app is not given
        }

        $this->app = $app;
    }

    /**
     * Boot the debugbarvscode
     */
    public function boot()
    {
        if ($this->booted) {
            return;
        }

        /** @var \ErlangParasu\DebugbarVscode\LaravelDebugbarVscode $debugbarvscode */
        $debugbarvscode = $this;

        /** @var Application $app */
        $app = $this->app;

        $this->booted = true;
    }

    /**
     * Modify the response and inject the debugbarvscode (or data in headers)
     *
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @param  \Symfony\Component\HttpFoundation\Response $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function modifyResponse(Request $request, Response $response)
    {
       if (! class_exists('\Barryvdh\Debugbar\LaravelDebugbar')
            || ! $this->app->make('\Barryvdh\Debugbar\LaravelDebugbar')->isEnabled()) {
            return $response;
        }

        try {
            $header = $request->header('Accept');
            if (strpos($header, 'html') !== false) {
                $this->injectDebugbarVscode($response);
            }
        } catch (\Exception $e) {
            $this->app['log']->error('DebugbarVscode exception: ' . $e->getMessage());
        }

        return $response;
    }

    /**
     * Injects the web debug toolbar into the given Response.
     *
     * @param \Symfony\Component\HttpFoundation\Response $response A Response instance
     * Based on https://github.com/symfony/WebProfilerBundle/blob/master/EventListener/WebDebugToolbarListener.php
     */
    public function injectDebugbarVscode(Response $response)
    {
        $content = $response->getContent();

        $this->loadViewsFrom(__DIR__.'/Resources', 'debugbarvscode');
        $renderer = view('debugbarvscode::vscode_debugbar_plugin');
        $renderedContent = $renderer->render();

        if (strpos($content, 'PhpDebugBar.') !== false) {
            return;
        }

        if (strpos($content, '</body>') !== false) {
            // OK
        } else {
            return;
        }

        $pos = strripos($content, '</body>');
        if (false !== $pos) {
            $content = substr($content, 0, $pos) . $renderedContent . substr($content, $pos);
        } else {
            $content = $content . $renderedContent;
        }

        // Update the new content and reset the content length
        $response->setContent($content);
        $response->headers->remove('Content-Length');
    }
}
