# Medical Cabinet Project

This document provides a professional and concise overview of the Medical Cabinet project, detailing its functionalities, installation process, and guidelines for team collaboration.

---

## Project Description
The Medical Cabinet application is designed for efficient management of doctors, patients, and appointments. It supports CRUD operations, features a user-friendly interface built with Twig, and includes an administration panel for resource management.

| **Aspect**             | **Details**                                                                                  |
|------------------------|----------------------------------------------------------------------------------------------|
| **Project Name**       | Medical Cabinet                                                                             |
| **Purpose**            | Manage healthcare operations efficiently                                                     |
| **Technologies Used**  | Symfony 6, Twig, Doctrine ORM, EasyAdminBundle                                               |

---

## Functional Requirements
| **Requirement**                      | **Description**                                                              |
|-------------------------------------|------------------------------------------------------------------------------|
| **Doctor Management**               | Create, read, update, and delete doctor profiles                             |
| **Patient Management**              | Handle patient profiles with full CRUD operations                             |
| **Appointment Scheduling**          | Enable patients to book and manage appointments with doctors                 |
| **Administrative Oversight**        | Provide an admin panel for resource and data management                      |

---

## Non-Functional Requirements
| **Requirement**                      | **Description**                                                              |
|-------------------------------------|------------------------------------------------------------------------------|
| **Scalability**                      | Support for increased data and concurrent users                              |
| **Performance**                      | Ensure fast response times and minimal latency                               |
| **Security**                         | Implement authentication, authorization, and data validation                 |
| **Usability**                        | Deliver a clean, intuitive user interface                                    |

---

## Installation and Setup

1. **Clone the Repository:**
   ```bash
   git clone <repository-url>
   cd medical-cabinet
   ```

2. **Install Symfony and Dependencies:**
   ```bash
   composer install
   composer require symfony/webapp-pack orm-pack form validator twig asset
   ```

3. **Configure Database:**
   - Update the `.env` file:
     ```env
     DATABASE_URL="mysql://username:password@127.0.0.1:3306/medical_cabinet"
     ```
   - Create the database:
     ```bash
     php bin/console doctrine:database:create
     ```

---

## Entity Design
| **Entity**             | **Fields**                     | **Relationships**                                   |
|------------------------|--------------------------------|---------------------------------------------------|
| **Doctor**             | name, email, speciality        | One-to-Many with Appointments                     |
| **Patient**            | name, email, phone            | One-to-Many with Appointments                     |
| **Appointment**        | date, time, status            | Many-to-One with Doctor, Many-to-One with Patient |

Generate entities and relationships:
```bash
php bin/console make:entity <EntityName>
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

---

## CRUD Operations
| **Module**             | **Details**
|------------------------|---------------------------------------------------------------------------------------------|
| **Doctor Management**  | Form: `DoctorType` | Controller: `DoctorController` | Actions: Create, Read, Update, Delete |
| **Patient Management** | Form: `PatientType` | Controller: `PatientController` | Full CRUD Functionality              |
| **Appointment System** | Form: `AppointmentType` | Manage availability, prevent conflicts                |

---

## Frontend Implementation
1. **Twig Template Example:**
   ```twig
   <h1>Register a Doctor</h1>
   {{ form_start(form) }}
   {{ form_widget(form) }}
   {{ form_end(form) }}
   ```

2. **Controller Sample:**
   ```php
   public function register(Request $request, EntityManagerInterface $em): Response
   {
       $doctor = new Doctor();
       $form = $this->createForm(DoctorType::class, $doctor);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           $em->persist($doctor);
           $em->flush();
           return $this->redirectToRoute('doctor_success');
       }

       return $this->render('doctor/register.html.twig', ['form' => $form->createView()]);
   }
   ```

---

## Administrative Features
Leverage EasyAdminBundle for efficient administration:
```bash
composer require easycorp/easyadmin-bundle
```
Configure dashboards to manage doctors, patients, and appointments seamlessly.

---

## Development and Deployment
1. **Run the Local Server:**
   ```bash
   symfony serve
   ```
2. **Version Control Workflow:**
   - Use Git branches for features and fixes.
   - Commit changes with descriptive messages.

---

## Team Collaboration Guidelines
| **Task**                | **Guidelines**                                                                |
|-------------------------|-------------------------------------------------------------------------------|
| **Feature Development** | Create a dedicated branch, test thoroughly, and submit a pull request.       |
| **Bug Tracking**        | Use the issue tracker. Assign and resolve bugs systematically.               |
| **Code Review**         | Ensure all commits are reviewed before merging into the main branch.         |

---

## Future Enhancements
- Implement calendar-based scheduling.
- Introduce notification emails for appointments.
- Enhance the user interface for better usability.


