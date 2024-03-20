<?php
use RuntimeException;

/**
 * Represents an SSH client for connecting to SSH servers and executing commands.
 *
 * This class provides functionality for connecting to an SSH server, authenticating using username/password,
 * and executing commands on the server.
 *
 * @throws RuntimeException If SSH2 extension is not installed.
 */
class SSH {
    /** @var resource|null The SSH connection resource. */
    private $connection;

    /** @var bool Indicates whether the SSH client is connected to a server. */
    public $connected = false;

    /** @var bool Indicates whether the SSH client is authenticated to the server. */
    public $authenticated = false;


    /**
     * Constructor.
     *
     * Initializes the SSH class.
     *
     * @throws RuntimeException If SSH2 extension is not installed.
     */
    public function __construct() {
        if (!extension_loaded("ssh2")) {
            throw new RuntimeException("SSH2 extension not installed, not able to connect to servers");
        }
    }

    /**
     * Connects to the SSH server.
     *
     * @param string $host The hostname or IP address of the SSH server.
     * @param string $port The port number of the SSH server.
     * @return bool True if connection successful, false otherwise.
     */
    public function connect(string $host, string $port) : bool {
        $this->connection = @ssh2_connect($host, $port);

        if (!$this->connection) {
            return false;
        }

        $this->connected = true;
        return true;
    }

    /**
     * Disconnects from the SSH server.
     */
    public function disconnect() : void {
        if($this->connected) {
            @ssh2_disconnect($this->connection);
            $this->connected = false;
        }
    }

    /**
     * Authenticates to the SSH server using username and password.
     *
     * @param string $username The username for authentication.
     * @param string $password The password for authentication.
     * @return string|bool True if authentication successful, false otherwise.
     */
    public function authenticate(string $username, string $password) : string | bool {
        if (!$this->connected) {
            return false;
        }

        if (!@ssh2_auth_password($this->connection, $username, $password)) {
            $this->disconnect();
            return false;
        }

        $this->authenticated = true; 
        return true;
    }

    /**
     * Executes a command on the SSH server.
     *
     * @param string $command The command to execute.
     * @return string The output of the command.
     */
    public function executeCommand(string $command) : string {
        if (!$this->connected) {
            return "Not connected to SSH server";
        }

        if (!$this->authenticated) {
            return "Not authenticated to SSH server";
        }

        $stream = @ssh2_exec($this->connection, $command); 
        if (!$stream) { 
            $this->disconnect();
            return "Command execution failed, disconnecting";
        }

        stream_set_blocking($stream, true);
        $output = stream_get_contents($stream);
        fclose($stream);
        
        return $output;
    }
}
