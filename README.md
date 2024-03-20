# SSH Client Class

## Overview

This PHP class represents an SSH client for connecting to SSH servers and executing commands. It provides functionality for connecting to an SSH server, authenticating using username/password, and executing commands on the server.

## Requirements

- PHP >= 5.3.0
- SSH2 extension

## Installation

Make sure the SSH2 extension is installed and enabled in your PHP environment.

## Exception Handling

- If the SSH2 extension is not installed, a `RuntimeException` will be thrown upon instantiation of the `SSH` class.

## Methods

### `__construct()`

- Initializes the SSH class.
- Throws a `RuntimeException` if SSH2 extension is not installed.

### `connect(string $host, string $port) : bool`

- Connects to the SSH server.
- Returns `true` if connection successful, `false` otherwise.

### `disconnect() : void`

- Disconnects from the SSH server.

### `authenticate(string $username, string $password) : string | bool`

- Authenticates to the SSH server using username and password.
- Returns `true` if authentication successful, `false` otherwise.

### `executeCommand(string $command) : string`

- Executes a command on the SSH server.
- Returns the output of the command.


## License

This class is released under the [MIT License](LICENSE). Feel free to modify and distribute it as needed. Contributions are welcome!
