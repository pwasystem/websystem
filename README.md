# WebSystem

WebSystem is a Content Management System (CMS) developed in 2001 to provide comprehensive tools for creating and maintaining websites during the Web 2.0 era. At that time, the absence of modern frameworks and development tools posed significant challenges in building internet applications, and WebSystem aimed to address these needs.

## Features

- **User Authentication:** Provides an access manager for user authentication and authorization.
- **Content Management:** Enables the creation, editing, and deletion of website content through an administrative interface.
- **Docker Integration:** Utilizes Docker for simplified deployment and environment consistency.

## Prerequisites

- **Docker:** Ensure Docker is installed on your system to facilitate containerized deployment.

## Installation

1. **Clone the Repository:**

   ```bash
   git clone https://github.com/pwasystem/websystem.git
   ```

2. **Navigate to the Project Directory:**

   ```bash
   cd websystem
   ```

3. **Start the Application Using Docker Compose:**

   ```bash
   docker-compose up -d
   ```

   This command will build and start the necessary containers in detached mode.

## Accessing the Application

- **Homepage:**

  Once the containers are running, access the homepage at:

  ```
  http://localhost
  ```

- **Admin Panel:**

  To manage content and settings, navigate to the admin panel at:

  ```
  http://localhost/admin
  ```

  Use the following credentials to log in:

  - **Username:** user
  - **Password:** pass

## Project Structure

```
websystem/
├── server/
│   ├── Dockerfile
│   └── ...
├── www/
│   ├── index.php
│   └── ...
├── README.md
├── docker-compose.yml
└── ...
```

- **server/:** Contains server-related configurations and the Dockerfile.
- **www/:** Houses the web application's PHP files.
- **docker-compose.yml:** Defines services, networks, and volumes for Docker Compose.

## Technologies Used

- **PHP:** Primary language for server-side scripting.
- **JavaScript:** Used for client-side interactions.
- **Docker:** Facilitates containerized deployment.

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request with your enhancements or bug fixes.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact

For questions or suggestions, please open an issue in the [GitHub repository](https://github.com/pwasystem/websystem).
