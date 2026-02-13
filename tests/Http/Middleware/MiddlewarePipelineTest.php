<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Tests\Http\Middleware;

use JonesRussell\PhpFigGuide\Http\Factory\ResponseFactory;
use JonesRussell\PhpFigGuide\Http\Message\Response;
use JonesRussell\PhpFigGuide\Http\Message\ServerRequest;
use JonesRussell\PhpFigGuide\Http\Middleware\AuthMiddleware;
use JonesRussell\PhpFigGuide\Http\Middleware\LoggingMiddleware;
use JonesRussell\PhpFigGuide\Http\Middleware\MiddlewarePipeline;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\AbstractLogger;

/**
 * Tests for PSR-15 Middleware implementations
 *
 * Tests LoggingMiddleware, AuthMiddleware, and MiddlewarePipeline
 * with realistic request/response scenarios.
 */
class MiddlewarePipelineTest extends TestCase
{
    private ResponseFactory $responseFactory;

    protected function setUp(): void
    {
        $this->responseFactory = new ResponseFactory();
    }

    // LoggingMiddleware tests

    public function testLoggingMiddlewareImplementsInterface(): void
    {
        $logger = new TestLogger();
        $middleware = new LoggingMiddleware($logger);

        $this->assertInstanceOf(MiddlewareInterface::class, $middleware);
    }

    public function testLoggingMiddlewareLogsRequestAndResponse(): void
    {
        $logger = new TestLogger();
        $middleware = new LoggingMiddleware($logger);

        $request = new ServerRequest('GET', 'http://example.com/api/posts');
        $handler = new TestHandler(new Response(200));

        $response = $middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertCount(2, $logger->logs);
        $this->assertStringContainsString('Request: GET', $logger->logs[0]);
        $this->assertStringContainsString('example.com', $logger->logs[0]);
        $this->assertStringContainsString('Response: 200', $logger->logs[1]);
    }

    // AuthMiddleware tests

    public function testAuthMiddlewareImplementsInterface(): void
    {
        $middleware = new AuthMiddleware($this->responseFactory);

        $this->assertInstanceOf(MiddlewareInterface::class, $middleware);
    }

    public function testAuthMiddlewareRejectsWithoutToken(): void
    {
        $middleware = new AuthMiddleware($this->responseFactory);

        $request = new ServerRequest('GET', 'http://example.com/api/posts');
        $handler = new TestHandler(new Response(200));

        $response = $middleware->process($request, $handler);

        $this->assertSame(401, $response->getStatusCode());
    }

    public function testAuthMiddlewareRejectsInvalidToken(): void
    {
        $middleware = new AuthMiddleware($this->responseFactory);

        $request = new ServerRequest(
            'GET',
            'http://example.com/api/posts',
            ['Authorization' => ['Bearer wrong-token']]
        );
        $handler = new TestHandler(new Response(200));

        $response = $middleware->process($request, $handler);

        $this->assertSame(401, $response->getStatusCode());
    }

    public function testAuthMiddlewareAcceptsValidToken(): void
    {
        $middleware = new AuthMiddleware($this->responseFactory);

        $request = new ServerRequest(
            'GET',
            'http://example.com/api/posts',
            ['Authorization' => ['Bearer valid-token']]
        );
        $handler = new TestHandler(new Response(200));

        $response = $middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function testAuthMiddlewareAcceptsCustomToken(): void
    {
        $middleware = new AuthMiddleware($this->responseFactory, 'my-secret');

        $request = new ServerRequest(
            'GET',
            'http://example.com/api/posts',
            ['Authorization' => ['Bearer my-secret']]
        );
        $handler = new TestHandler(new Response(200));

        $response = $middleware->process($request, $handler);

        $this->assertSame(200, $response->getStatusCode());
    }

    // MiddlewarePipeline tests

    public function testPipelineImplementsHandlerInterface(): void
    {
        $pipeline = new MiddlewarePipeline(new TestHandler(new Response()));

        $this->assertInstanceOf(RequestHandlerInterface::class, $pipeline);
    }

    public function testEmptyPipelineDelegatesToFallbackHandler(): void
    {
        $handler = new TestHandler(new Response(204));
        $pipeline = new MiddlewarePipeline($handler);

        $request = new ServerRequest('GET', 'http://example.com/');
        $response = $pipeline->handle($request);

        $this->assertSame(204, $response->getStatusCode());
    }

    public function testPipelineExecutesMiddlewareInOrder(): void
    {
        $order = [];

        $middleware1 = new OrderTrackingMiddleware($order, '1');
        $middleware2 = new OrderTrackingMiddleware($order, '2');

        $handler = new TestHandler(new Response(200));
        $pipeline = new MiddlewarePipeline($handler);
        $pipeline->pipe($middleware1);
        $pipeline->pipe($middleware2);

        $request = new ServerRequest('GET', 'http://example.com/');
        $pipeline->handle($request);

        $this->assertSame(['1-before', '2-before', '2-after', '1-after'], $order);
    }

    public function testFullMiddlewareChainWithLoggingAndAuth(): void
    {
        $logger = new TestLogger();

        $pipeline = new MiddlewarePipeline(new TestHandler(new Response(200)));
        $pipeline->pipe(new LoggingMiddleware($logger));
        $pipeline->pipe(new AuthMiddleware($this->responseFactory));

        // Test with valid auth
        $request = new ServerRequest(
            'GET',
            'http://example.com/api/posts',
            ['Authorization' => ['Bearer valid-token']]
        );
        $response = $pipeline->handle($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertCount(2, $logger->logs);
    }

    public function testFullMiddlewareChainBlocksUnauthorized(): void
    {
        $logger = new TestLogger();

        $pipeline = new MiddlewarePipeline(new TestHandler(new Response(200)));
        $pipeline->pipe(new LoggingMiddleware($logger));
        $pipeline->pipe(new AuthMiddleware($this->responseFactory));

        // Test without auth
        $request = new ServerRequest('GET', 'http://example.com/api/posts');
        $response = $pipeline->handle($request);

        $this->assertSame(401, $response->getStatusCode());
        // Logger should still log request and the 401 response
        $this->assertCount(2, $logger->logs);
        $this->assertStringContainsString('Response: 401', $logger->logs[1]);
    }

    public function testPipelineMethodChaining(): void
    {
        $logger = new TestLogger();
        $handler = new TestHandler(new Response(200));

        $pipeline = (new MiddlewarePipeline($handler))
            ->pipe(new LoggingMiddleware($logger))
            ->pipe(new AuthMiddleware($this->responseFactory));

        $this->assertInstanceOf(MiddlewarePipeline::class, $pipeline);
    }
}

/**
 * Simple test logger that captures log messages.
 */
class TestLogger extends AbstractLogger
{
    /** @var string[] */
    public array $logs = [];

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->logs[] = (string) $message;
    }
}

/**
 * Simple test handler that returns a predefined response.
 */
class TestHandler implements RequestHandlerInterface
{
    public function __construct(private ResponseInterface $response)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->response;
    }
}

/**
 * Middleware that tracks execution order for testing.
 */
class OrderTrackingMiddleware implements MiddlewareInterface
{
    /**
     * @param array  &$order Reference to order tracking array
     * @param string $id     Identifier for this middleware instance
     */
    public function __construct(
        private array &$order,
        private string $id,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->order[] = $this->id . '-before';
        $response = $handler->handle($request);
        $this->order[] = $this->id . '-after';
        return $response;
    }
}
