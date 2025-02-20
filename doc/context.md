# ScholarPath Documentation

## 🛠 Technology Stack

### Backend Framework
- **Laravel 10+**
  - Modern PHP framework powering our backend API
  - Role-based authentication and middleware
  - Blade templating with Tailwind CSS
  - Database migrations and seeders

### Frontend Technologies
- **Tailwind CSS**
  - Utility-first CSS framework
  - Responsive design system
  - Custom components and layouts
  - Modern UI/UX patterns

### Database
- **MySQL**
  - Robust relational database
  - Scholarship and application management
  - User role management
  - Efficient data relationships

## 📦 Core Components

### Models
1. **User**
   - Role-based access (admin/student)
   - Authentication management
   - Profile information
   - Relationship with applications

2. **Scholarship**
   - Name and description
   - Type (academic/presidential/ched)
   - Discount percentage
   - Requirements list
   - Status management

3. **ScholarshipApplication**
   - Student information
   - Academic details (GPA, course)
   - Application status
   - Timestamps and tracking

### Controllers
1. **Admin Controllers**
   - DashboardController: Admin overview
   - ScholarshipController: Scholarship management
   - Reports and application processing

2. **Student Controllers**
   - DashboardController: Student overview
   - ScholarshipController: Application management
   - Profile and status tracking

### Middleware
- **CheckRole**
  - Role-based access control
  - Route protection
  - User authorization

## 🎨 Views and Templates

### Admin Views
1. **Dashboard**
   - Overview statistics
   - Recent applications
   - Quick actions

2. **Scholarship Management**
   - List all scholarships
   - Create/edit scholarships
   - View scholarship details
   - Application processing

3. **Reports**
   - Generate filtered reports
   - Export functionality
   - Status summaries

### Student Views
1. **Dashboard**
   - Available scholarships
   - Application status
   - Profile management

2. **Applications**
   - Application form
   - Status tracking
   - Document submission

## 🔐 Authentication Flow
1. User registers/logs in
2. Role checked via middleware
3. Redirected to role-specific dashboard
4. Access controlled by CheckRole middleware

## 📊 Database Schema

### users
- id (primary key)
- name
- email (unique)
- password
- role (admin/student)
- timestamps

### scholarships
- id (primary key)
- name
- description
- type
- discount_percentage
- requirements (JSON)
- status
- timestamps

### scholarship_applications
- id (primary key)
- user_id (foreign key)
- scholarship_id (foreign key)
- gpa
- lowest_grade
- course
- year_level
- academic_year
- semester
- status
- timestamps

## 🚀 Getting Started

1. Clone the repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env`
4. Generate key: `php artisan key:generate`
5. Configure database in `.env`
6. Run migrations: `php artisan migrate`
7. Seed database: `php artisan db:seed`
8. Start server: `php artisan serve`

## 👥 Test Users
- Admin: admin@scholarpath.test (password: password)
- Student: student@scholarpath.test (password: password)

## 🔄 Workflow

### Student Workflow
1. Log in as student
2. Browse available scholarships
3. Submit application with required info
4. Track application status

### Admin Workflow
1. Log in as admin
2. Manage scholarships
3. Process applications
4. Generate reports