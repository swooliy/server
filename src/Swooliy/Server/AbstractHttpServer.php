<?php 

namespace Swooliy\Server;

use Exception;

/**
 * Abstract Http Server  base on Swoole Http Server
 * 
 * @category Http_Server
 * @package  Swooliy\Lumen
 * @author   ney <zoobile@gmail.com>
 * @license  MIT 
 * @link     https://github.com/swooliy/server
 */
abstract class AbstractHttpServer
{
    protected $server;

    /**
     * Construct Abstract Http Server
     *
     * @param string     $host    server's host
     * @param string|int $port    server's port
     * @param array      $options server's options
     */
    public function __construct($host, $port, $options)
    {
        if (!class_exists('\swoole_http_server')) {
            throw new Exception("The command need php extension swoole");
        }

        $this->server = new \swoole_http_server($host, $port);

        $this->server->on('start', [$this, 'onMasterStarted']);
        $this->server->on('managerStart', [$this, 'onManagerStarted']);
        $this->server->on('workerStart', [$this, 'onWorkerStarted']);
        $this->server->on('request', [$this, 'onRequest']);
        $this->server->on('shutdown', [$this, 'onShutdown']);
        $this->server->on('workerStop', [$this, 'onWorkerStopped']);
        $this->server->on('workerError', [$this, 'onWorkerError']);
        $this->server->on('managerStop', [$this, 'onManagerStopped']);

        $this->server->set($options);

    }

    /**
     * Start the server
     *
     * @return void
     */
    public function start()
    {
        $this->server->start();
    }

    /**
     * Callback when swoole http server's master process created.
     *
     * @param Swoole\Http\Server $server swoole server instance
     * 
     * @return void 
     */
    public abstract function onMasterStarted($server);

    /**
     * Callback when swoole http server's manager process created.
     *
     * @param Swoole\Http\Server $server swoole server instance
     * 
     * @return void 
     */
    public abstract function onManagerStarted($server);

    /**
     * Callback when swoole http server's worker process created.
     *
     * @param Swoole\Http\Server $server   swoole server instance
     * @param int                $workerId current worker proccess's pid
     * 
     * @return void 
     */
    public abstract function onWorkerStarted($server, $workerId);

    /**
     * Callback when swoole http server's worker process received http messages.
     *
     * @param Swoole\Http\Request  $swRequest  current swoole request instance
     * @param Swoole\Http\Response $swResponse current swoole response instance
     * 
     * @return void 
     */
    public abstract function onRequest($swRequest, $swResponse);

    /**
     * Callback when swoole http server shutdown.
     *
     * @param Swoole\Http\Server $server swoole server instance
     * 
     * @return void
     */
    public abstract function onShutdown($server);

    /**
     * Callback when swoole http server's worker process stopped.
     *
     * @param Swoole\Http\Server $server   swoole server instance
     * @param int                $workerId the order number of the worker process
     * 
     * @return void
     */
    public abstract function onWorkerStopped($server, $workerId);

    /**
     * Callback when swoole http server's worker process happen error.
     *
     * @param Swoole\Http\Server $server    swoole server instance
     * @param int                $workerId  the order number of the worker process
     * @param int                $workerPid the pid of the worker process
     * @param int                $exitCode  the status code return when the process exited
     * @param int                $signal    the signal when the process exited
     * 
     * @return void
     */
    public abstract function onWorkerError($server, $workerId, $workerPid, $exitCode, $signal);

    /**
     * Callback when swoole http server's manager process stopped.
     *
     * @param Swoole\Http\Server $server swoole server instance
     * 
     * @return void
     */
    public abstract function onManagerStopped($server);





}