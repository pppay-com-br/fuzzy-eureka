# README

## Overview

This project implements a high-performance HTTP endpoint capable of handling 6,000 POST requests per minute. It meets the following requirements:

1. **Endpoint Functionality**: An HTTP POST endpoint that accepts:
   - `id` (UUID): A unique identifier.
   - `github_username` (string): The GitHub username.
   - `commit_hash` (string): The commit hash.

2. **Data Persistence**: The received data is stored in a PostgreSQL database.

3. **Performance**: The endpoint supports 6,000 requests per minute, ensuring data is saved without loss.

4. **Tools and Technologies**: The solution leverages Laravel with Octane, PgBouncer, FrankenPHP, and Supervisord for scalability and efficiency.

---

## System Design

### **Endpoint**
The system provides a single POST endpoint:

#### URL:
```
POST /request
```
or
```
POST http://localhost:8000/request
```

#### Payload:
```json
{
  "id": "<uuid>",
  "github_username": "<github_username>",
  "commit_hash": "<commit_hash>"
}
```

#### Response:
- **Success**: 200 Ok
```json
void
```

- **Validation Error**: 422 Unprocessable Entity
```json
{
  "message": "The id field is required (and 2 more errors)",
  "errors": {
    "id": ["The id field is required."],
    "github_username": ["The github_username field is required."],
    "commit_hash": ["The commit_hash field is required."]
  }
}
```

### **Database Schema**
The data is stored in a PostgreSQL database with the following table:

```sql
CREATE TABLE requests (
    id UUID NOT NULL PRIMARY KEY,
    github_username VARCHAR(60) NOT NULL,
    commit_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_github_username ON requests (github_username);
CREATE INDEX idx_commit_hash ON requests (commit_hash);
```

### **Architecture**

#### **Technologies Used**
- **Laravel Framework**: For building the API and managing the application.
- **Laravel Octane**: For high-concurrency request handling using FrankenPHP and Caddy server (Go-based).
- **PgBouncer**: For PostgreSQL connection pooling, enabling high performance.
- **PostgreSQL**: The primary database for storing requests.
- **FrankenPHP**: A performance-oriented PHP server.
- **Supervisord**: Process management to run and monitor the application.

#### **System Workflow**
1. Incoming requests are sent to the `/request` endpoint.
2. The payload is validated.
3. The validated data is inserted into the PostgreSQL database.
4. PgBouncer manages efficient database connections, enabling scalability.
5. Supervisord ensures that services such as Laravel Octane and FrankenPHP are running continuously.
6. Requests for previously retrieved data are cached to optimize read performance.

---

## Project Folder Structure

The project follows a clean and modular structure, adhering to the principles of separation of concerns and scalability:

```
.
├── App
│   ├── Contracts
│   │   ├── Repositories
│   │   ├── Services
│   ├── Repositories
│   ├── Services
│   ├── Http
│   │   ├── Controllers
│   │   ├── Resources
│   │   ├── Requests
│   │   ├── Middleware
│   ├── Models
│   ├── Providers
├── docker
│   ├── entrypoint.sh
│   ├── supervisord.conf
├── pgbouncer
│   ├── pgbouncer.ini
│   ├── userlist.txt
├── public
├── resources
├── routes
├── storage
├── tests
└── vendor
```

### Key Directories

- **`App/Contracts`**: Contains interfaces for repositories and services, ensuring dependency inversion.
  - **Repositories**: Define repository interfaces.
  - **Services**: Define service interfaces.

- **`App/Repositories`**: Contains concrete implementations of repository interfaces for database operations.

- **`App/Services`**: Contains service implementations that encapsulate business logic.

- **`App/Http/Controllers`**: Handles HTTP requests and responses.

- **`App/Http/Resources`**: Defines resource classes for transforming data.

- **`App/Http/Requests`**: Contains request validation logic.

- **`App/Http/Middleware`**: Middleware for handling request lifecycle tasks.

- **`App/Models`**: Contains Eloquent models for database interaction.

- **`App/Providers`**: Service providers for application bootstrapping.

- **`docker`**: Contains Docker-specific configuration files.
  - `entrypoint.sh`: Entrypoint script for setting up the application.
  - `supervisord.conf`: Configuration for managing processes using Supervisord.

- **`pgbouncer`**: Contains PgBouncer configuration files.
  - `pgbouncer.ini`: PgBouncer settings for connection pooling.
  - `userlist.txt`: User authentication configuration for PgBouncer.

---

## Installation and Setup

### **Prerequisites**
- Docker and Docker Compose

### **Setup Instructions**

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/felipebrsk/fuzzy-eureka
   cd fuzzy-eureka
   ```

2. **Build and Start Services**:
   Use Docker Compose to start the application, PostgreSQL, and PgBouncer.
   ```bash
   docker-compose up --build -d
   ```

3. **Access the Application**:
   The application will be available at:
   ```
   http://localhost:8000/up
   ```

---

## Testing

### **Performance Testing**
The system has been validated using **Vegeta**, a powerful load testing tool, with the following configuration:

```go
package main

import (
	"os"
	"time"

	"github.com/google/uuid"
	vegeta "github.com/tsenart/vegeta/lib"
	"net/http"
)

func customTargeter() vegeta.Targeter {
	return func(tgt *vegeta.Target) error {
		if tgt == nil {
			return vegeta.ErrNilTarget
		}

		tgt.Method = "POST"

		tgt.URL = "http://localhost:8000/request"
		merchantTransactionID := uuid.New().String()
		payload := `{
            "id": "` + merchantTransactionID + `",
            "github_username": "vegeta",
            "commit_hash": "vegeta#hash"
        }`

		tgt.Body = []byte(payload)

		header := http.Header{}
		header.Add("Accept", "application/json")
		header.Add("Content-Type", "application/json")
		tgt.Header = header

		return nil
	}
}

func main() {
	rate := vegeta.Rate{Freq: 100, Per: time.Second}
	duration := 1 * time.Minute

	targeter := customTargeter()
	attacker := vegeta.NewAttacker()

	var metrics vegeta.Metrics
	for res := range attacker.Attack(targeter, rate, duration, "Whatever name") {
		metrics.Add(res)
	}
	metrics.Close()

	reporter := vegeta.NewTextReporter(&metrics)
	reporter(os.Stdout)
}
```

### **Run the Test**:
Execute the Vegeta script to validate performance:
```bash
go run vegeta_test.go
```

---

## Tools and Technologies
- **Laravel Octane**: High-performance request handling.
- **PgBouncer**: Efficient PostgreSQL connection pooling.
- **PostgreSQL**: Database for data persistence.
- **FrankenPHP**: Performance-oriented PHP server.
- **Supervisord**: Process management.
- **Docker**: Containerization of the application and services.
- **Caching**: Integrated caching using Laravel's cache mechanism to improve read performance.

---

## Additional Features (Optional)
### Get Requests by GitHub Username
An optional GET endpoint can retrieve requests by GitHub username:

#### URL:
```
GET /users/<username>/requests
```

#### Response:
```json
[
  {
    "id": "<uuid>",
    "github_username": "<github_username>",
    "commit_hash": "<commit_hash>",
    "created_at": "<timestamp>"
  }
]
```

---

## Conclusion
This system is designed to handle high-concurrency scenarios efficiently while ensuring data integrity and scalability. By leveraging Laravel Octane, PgBouncer, FrankenPHP, and Supervisord, it achieves the required performance of 6,000 requests per minute while maintaining reliability and simplicity.
