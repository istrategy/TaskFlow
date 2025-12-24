# TaskFlow

A Laravel-based project and task management application with team collaboration features.

## Project Overview

TaskFlow is a comprehensive project management system that allows users to:

- Create and manage projects
- Track tasks with status management (pending, in progress, completed)
- Assign tasks to team members
- Add comments to tasks for collaboration
- View task statistics and completion progress
- Receive email notifications on task updates

## Prerequisites

Before you begin, ensure you have the following installed:

- **Docker** - [Install Docker](https://docs.docker.com/get-docker/)
- **Docker Compose** - Usually included with Docker Desktop
- **Make** - For running Makefile commands
- **Composer** - [Install Composer](https://getcomposer.org/) (for initial setup)

## Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd TaskFlow
   ```

2. **Initialize the project**
   ```bash
   make init
   ```
   This command will:
   - Install Composer dependencies
   - Copy `.env.example` to `.env`
   - Start Laravel Sail containers
   - Generate application key
   - Run database migrations
   - Seed the database with demo data

3. **Access the application**
   
   Open your browser and navigate to the URLs below.

## Access URLs

| Service | URL |
|---------|-----|
| **Application** | http://localhost |
| **Mailhog (Email Testing)** | http://localhost:8025 |

## How to Run Console Commands

Use the `make artisan` command to run any Laravel Artisan command:

```bash
# Run migrations
make artisan cmd="migrate"

# Check migration status
make artisan cmd="migrate:status"

# Generate task report
make artisan cmd="report:tasks"

# Export tasks to CSV
make artisan cmd="report:export"

# Clear cache
make artisan cmd="cache:clear"
```

### Available Make Targets

| Command | Description |
|---------|-------------|
| `make up` | Start containers |
| `make down` | Stop containers |
| `make init` | Full project initialization |
| `make artisan cmd="..."` | Run Artisan commands |
| `make reset` | Rebuild and reinitialize clean environment |
| `make logs` | View Laravel logs (tail) |
| `make help` | Show available targets |

## Example Seeded Credentials

After running `make init` or `make artisan cmd="db:seed"`, you can log in with:

| Field | Value |
|-------|-------|
| **Email** | `demo@taskflow.com` |
| **Password** | `password` |

The seeder also creates:
- 6 users (1 demo + 5 random)
- 8 projects
- ~44 tasks with various statuses
- ~88 comments

## Implemented Features

### Core Features
- **User Authentication** - Registration, login, logout with Laravel Breeze
- **Project Management** - CRUD operations for projects
- **Task Management** - Create, update, delete tasks with status tracking
- **Comments System** - Add and delete comments on tasks
- **Task Assignment** - Assign tasks to team members

### Authorization & Policies
- Project owners have full control over their projects and tasks
- Task assignees can view all tasks in assigned projects but only edit their own
- Users can only delete their own comments

### Events & Notifications
- `TaskUpdated` event fires when tasks are modified
- Email notifications sent to assignees on task updates
- Mailhog integration for email testing

### Console Commands
- `php artisan report:tasks` - Display task statistics in terminal
- `php artisan report:export` - Export tasks to CSV file

### Database
- Eloquent ORM with relationships (User, Project, Task, Comment)
- Factories and seeders for demo data generation
- Task history tracking

### Collections & Statistics
- Task statistics on project pages (total, completed, in progress, pending)
- Completion percentage with progress bar

## Development

### Starting the Development Server
```bash
make up
```

### Stopping the Server
```bash
make down
```

### Viewing Logs
```bash
make logs
```

### Resetting the Environment
```bash
make reset
```
This will wipe all data and start fresh with seeded demo data.

