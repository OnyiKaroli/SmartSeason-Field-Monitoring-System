# SmartSeason Field Monitoring System

SmartSeason is a streamlined field monitoring system designed to help agricultural coordinators and field agents track crop progress throughout the growing season. This system demonstrates a disciplined approach to full-stack development, featuring role-based access control, automated field status computation, and a polished user interface.

## 🚀 Overview

The primary goal of SmartSeason is to bridge the gap between field observations and management oversight. Agricultural coordinators (Admins) can manage fields and assign them to agents, while Field Agents provide real-time updates and observations directly from the field.

## ✨ Features

- **Role-Based Access Control**: Strict separation between Admin and Field Agent capabilities.
- **Field Management**: Full CRUD lifecycle for fields, including assignment workflows.
- **Progress Tracking**: Real-time stage updates (Planted, Growing, Ready, Harvested) with detailed observations and history.
- **Computed Field Status**: Automated assessment of field health (Active, At Risk, Completed) based on activity and stage progression.
- **Dynamic Dashboards**: Role-specific insights for both Admins (global overview) and Agents (assigned focus).
- **Activity Timeline**: Complete chronological history of updates for every field.
- **Filtering & Search**: Robust tools to find fields by status, stage, crop type, or assigned agent.

## 🛠 Tech Stack

- **Framework**: [Laravel 11](https://laravel.com)
- **Database**: [SQLite](https://www.sqlite.org) (chosen for portability and ease of review)
- **Styling**: [Tailwind CSS](https://tailwindcss.com) with [Laravel Blade](https://laravel.com/docs/blade)
- **Authentication**: [Laravel Breeze](https://laravel.com/docs/starter-kits) (customized for role-based logic)
- **Testing**: [PHPUnit](https://phpunit.de) (Feature & Unit tests)

## 👥 System Roles

### Admin (Coordinator)
- Full visibility across all fields in the system.
- Can create, edit, and delete field records.
- Responsible for assigning fields to Field Agents.
- Access to a global dashboard showing system-wide statistics and agent activity.

### Field Agent
- Focused view showing only assigned fields.
- Authorized to submit stage updates and notes for their assigned fields.
- Access to a personalized dashboard highlighting fields that need immediate attention.

## 🧠 Field Status Logic

The status of a field is automatically computed based on the following business rules:

- **Completed**: The field's current stage is set to **Harvested**.
- **At Risk**: The field is not harvested and meets any of the following criteria:
    - No updates or observations have been recorded in more than **7 days**.
    - The field has remained in the same stage for more than **45 days** (indicating potential stagnation).
- **Active**: All other fields that are not harvested and are receiving regular updates.

Each field detail page provides a clear reason for its current status (e.g., "No updates received in 9 days").

## 📐 Design Decisions & Assumptions

1. **SQLite for Portability**: I chose SQLite to ensure the project can be run immediately by a reviewer without configuring a full MySQL/PostgreSQL server.
2. **Computed vs. Cached Status**: Field status is computed on-the-fly to ensure it always reflects the latest business rules, rather than relying on manual updates or brittle database triggers.
3. **Internal Assignment**: It is assumed that only Admins can assign fields, and only users with the `field_agent` role can be assigned.
4. **Registration**: While default registration routes exist, the intended flow is for Admins to manage user accounts. New users default to the `field_agent` role.

## ⚙️ Setup Instructions

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM

### Installation Steps

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd SmartSeason-Field-Monitoring-System
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**:
   The project is pre-configured to use SQLite. Ensure an empty database file exists:
   ```bash
   touch database/database.sqlite
   ```

5. **Run Migrations & Seeders**:
   This will set up the schema and populate the system with realistic demo data.
   ```bash
   php artisan migrate --seed
   ```

## 🧪 Running Tests

The project includes a comprehensive suite of unit and feature tests.

```bash
# Run all tests
php artisan test
```

Tests cover:
- **Unit**: Field Status Logic, Dashboard Calculations, Policy Rules.
- **Feature**: Authentication, Field CRUD, Role-based Access, Update Submissions.

## 🔐 Demo Credentials

Use these accounts to explore the system:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@smartseason.test` | `password` |
| **Field Agent** | `agent1@smartseason.test` | `password` |
| **Field Agent** | `agent2@smartseason.test` | `password` |

## 📈 Trade-offs & Future Improvements

- **Scalability**: For a production system with thousands of fields, I would migrate to MySQL/PostgreSQL and implement status caching with scheduled invalidation.
- **Mobile App**: While the UI is responsive, a dedicated mobile app using Flutter or React Native would provide better offline support for agents in remote fields.
- **Geospatial Data**: Adding GIS coordinates and map views would enhance the "Field Monitoring" aspect significantly.
