<?php

/**
 * PHP-FIG Standards Blog API Demo
 *
 * A runnable demonstration showing how PSR standards work together
 * in a blog API context. This is NOT a production server.
 *
 * Run with: php public/index.php
 */

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use JonesRussell\PhpFigGuide\Blog\Post;
use JonesRussell\PhpFigGuide\Clock\SystemClock;
use JonesRussell\PhpFigGuide\Event\PostCreatedEvent;
use JonesRussell\PhpFigGuide\Event\SimpleEventDispatcher;
use JonesRussell\PhpFigGuide\Event\SimpleListenerProvider;
use JonesRussell\PhpFigGuide\Http\Factory\ResponseFactory;
use JonesRussell\PhpFigGuide\Http\Factory\StreamFactory;
use JonesRussell\PhpFigGuide\Http\Message\ServerRequest;
use JonesRussell\PhpFigGuide\Http\Middleware\AuthMiddleware;
use JonesRussell\PhpFigGuide\Http\Middleware\MiddlewarePipeline;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

echo "=== PHP-FIG Standards Blog API Demo ===" . PHP_EOL . PHP_EOL;

// --- PSR-20: Clock ---
echo "--- PSR-20: Clock ---" . PHP_EOL;
$clock = new SystemClock();
echo "Current time (PSR-20 Clock): " . $clock->now()->format('Y-m-d H:i:s') . PHP_EOL;
echo PHP_EOL;

// --- PSR-14: Events ---
echo "--- PSR-14: Event Dispatcher ---" . PHP_EOL;
$listenerProvider = new SimpleListenerProvider();
$listenerProvider->addListener(PostCreatedEvent::class, function (PostCreatedEvent $event): void {
    echo "  Event listener: Post '{$event->getPost()->getTitle()}' was created!" . PHP_EOL;
});
$dispatcher = new SimpleEventDispatcher($listenerProvider);

// --- Blog Domain ---
echo PHP_EOL . "--- Blog Domain ---" . PHP_EOL;
$post = new Post(1, 'Hello PSR World', 'A post about PHP standards', 'hello-psr-world');
echo "Created post: " . $post->getTitle() . " (slug: " . $post->getSlug() . ")" . PHP_EOL;

$post->publish($clock->now());
echo "Published: " . ($post->isPublished() ? 'yes' : 'no') . PHP_EOL;
echo "Published at: " . $post->getPublishedAt()->format('Y-m-d H:i:s') . PHP_EOL;

// Dispatch event
echo PHP_EOL . "Dispatching PostCreatedEvent..." . PHP_EOL;
$dispatcher->dispatch(new PostCreatedEvent($post, $clock->now()));

// --- PSR-17: HTTP Factories + PSR-7: HTTP Messages ---
echo PHP_EOL . "--- PSR-17: HTTP Factories ---" . PHP_EOL;
$responseFactory = new ResponseFactory();
$streamFactory = new StreamFactory();
$response = $responseFactory->createResponse(200);
echo "Created response: HTTP " . $response->getStatusCode() . " " . $response->getReasonPhrase() . PHP_EOL;

$stream = $streamFactory->createStream('Hello from StreamFactory!');
echo "Created stream: " . $stream . PHP_EOL;

// --- PSR-15: Middleware Pipeline ---
echo PHP_EOL . "--- PSR-15: Middleware Pipeline ---" . PHP_EOL;

// Create a simple handler that returns post data as JSON
$handler = new class ($responseFactory, $streamFactory) implements RequestHandlerInterface {
    public function __construct(
        private ResponseFactory $responseFactory,
        private StreamFactory $streamFactory,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = [
            'message' => 'Blog API response',
            'method' => $request->getMethod(),
            'path' => $request->getUri()->getPath(),
        ];
        $body = $this->streamFactory->createStream(json_encode($data, JSON_PRETTY_PRINT));
        return $this->responseFactory->createResponse(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);
    }
};

// Build pipeline with auth middleware
$pipeline = new MiddlewarePipeline($handler);
$pipeline->pipe(new AuthMiddleware($responseFactory, 'demo-token'));

// Test with valid auth
echo PHP_EOL . "1) Authenticated request (valid token):" . PHP_EOL;
$request = new ServerRequest('GET', '/api/posts');
/** @var ServerRequestInterface $request */
$request = $request->withHeader('Authorization', 'Bearer demo-token');
$response = $pipeline->handle($request);
echo "   Status: HTTP " . $response->getStatusCode() . " " . $response->getReasonPhrase() . PHP_EOL;
echo "   Body: " . $response->getBody() . PHP_EOL;

// Test without auth
echo PHP_EOL . "2) Unauthenticated request (no token):" . PHP_EOL;
$badRequest = new ServerRequest('GET', '/api/posts');
$badResponse = $pipeline->handle($badRequest);
echo "   Status: HTTP " . $badResponse->getStatusCode() . " " . $badResponse->getReasonPhrase() . PHP_EOL;

// Test with wrong token
echo PHP_EOL . "3) Request with invalid token:" . PHP_EOL;
$wrongRequest = new ServerRequest('GET', '/api/posts');
/** @var ServerRequestInterface $wrongRequest */
$wrongRequest = $wrongRequest->withHeader('Authorization', 'Bearer wrong-token');
$wrongResponse = $pipeline->handle($wrongRequest);
echo "   Status: HTTP " . $wrongResponse->getStatusCode() . " " . $wrongResponse->getReasonPhrase() . PHP_EOL;

echo PHP_EOL . "=== Demo Complete ===" . PHP_EOL;
