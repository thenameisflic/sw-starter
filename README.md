# SWStarter

SWStarter is a Star Wars API explorer built with Laravel (backend) and React (frontend). It allows users to search for Star Wars characters (people) and movies, and view detailed information about them. It is designed to be user-friendly, responsive, and efficient.

## Additional Features
Besides basic search features and exploring details of records, some features I'd like to highlight are:
1. Responsive design

I've leveraged TailwindCSS to design for a fixed set of screen sizes, which include mobile devices.

2. Dark mode support

The UI will automatically adapt to your system's preferences. If your device is set to Dark Mode, the application will switch to a dark theme.

3. Basic animations

The UI incorporates subtle animations powered by `motion`. They are designed to make interactions smoother, more engaging and visually appealing without getting in the way.

4. Cached responses

To improve performance and reduce redundant API calls, nested API records are cached.

5. Query statistics

The application includes a /statistics endpoint that provides insights into query usage and response times. Statistics are computed immediately on the first access and subsequently updated every 5 minutes.

# Getting Started
### Prerequisites
The setup has been tested with MacOS 15 and Debian 12.
 1. Docker: Ensure Docker is installed on your machine.
 2. Ports: Make sure ports 8000 (backend) and 5173 (frontend) are available on your host machine.

### Running the Application with Docker

1. Clone the repository to your local machine.

2. Navigate to the project folder in your terminal.

3. Run the following command to start the application:
    `docker compose up`

4. Once the containers are up and running, open your browser and navigate to:
    http://localhost:8000

### Notes on Docker Setup
The provided Dockerfile and docker-compose configuration are intended for demo purposes only. They are not optimized for production or development environments.
For maximum compatibility, we don't use volumes.
