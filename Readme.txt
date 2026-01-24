StudyTask – Project README

1. Project Overview

StudyTask is a web-based task and group management application developed as part of the university course “Software Project”.

The goal of the project is to provide a structured platform that supports students in organizing study-related tasks within groups. The application focuses on collaborative task management, clear workflows, and transparent progress tracking.

The application allows users to:
- register and log in securely,
- create and manage study groups,
- create, assign and track tasks with deadlines and status workflows,
- visualize tasks in a calendar view,
- analyze progress using basic statistics,
- and manage users and contact messages via an admin interface.

In addition to the functional implementation, strong emphasis was placed on documentation, reproducibility, and a structured development process.


2. Source Code & Project Access (GitHub)

The complete project source code is available in the following GitHub repository:

https://github.com/oemerue/StudyTask

The repository contains:
- the full frontend and backend source code,
- all documentation required for setup, usage, and evaluation,
- and the database dump required to run the application locally.

All documents referenced in this README can be found directly within the project repository, primarily in the `docs` directory.


3. Design & Prototyping (Figma)

At the beginning of the project, a visual prototype of the application was created using Figma.  
The prototype was used to define the layout, navigation structure, and core user flows before starting the technical implementation.

The Figma prototype served as a design reference throughout development and helped align frontend structure and user experience early in the project.

The prototype can be accessed via the following link:
https://www.figma.com/design/xNRuJ4pEoI9QU0NSKNwm2C/Untitled?node-id=0-1&t=mxnw7tOVuRfGNiSm-1


4. Project / Repository Structure

The repository is structured as follows:

StudyTask/
- Backend/        PHP backend (API endpoints, authentication, database access)
- Frontend/       HTML, CSS and JavaScript frontend
- docs/           Complete project documentation
  - database/     SQL dump for database setup
  - Setup & Installation Guide
  - User Manual
  - Technical Documentation
  - Process / PM Documentation
  - Evidence for grading criteria
  - Reflection
  - Personal Contribution
  - Personal Development
- index.html      Entry point of the application

All documents required for understanding, running, and evaluating the project are located inside the `docs` folder of the repository.


5. Getting Started (Local Setup)

To run the project locally, please refer to the “Installation & Setup Guide” located in the `docs` folder of the GitHub repository.

The setup guide explains step by step:
- required software (XAMPP, Apache, MySQL),
- correct placement of the project files,
- creation of the database using phpMyAdmin,
- import of the provided SQL dump,
- configuration of the database connection,
- and how to start and test the application.

After successful setup, the application can be accessed via:
http://localhost/StudyTask


6. Documentation Overview

All documentation required for the evaluation of the project is located in the `docs` directory of the repository.

The documentation includes:

- Installation & Setup Guide  
  Instructions on how to install and run the application locally, including database initialization.

- User Manual  
  Description of the application from an end-user perspective, including group management, task handling, calendar view, statistics, and admin functionality.

- Technical Documentation  
  High-level overview of the system architecture, technologies used, authentication and authorization concepts, and database design.

- Process / Project Management Documentation  
  Documentation of the development process, including process model, backlog and task management, sprint and milestone reviews, time tracking, and quality assurance.

- Evidence for Grading Criteria  
  Direct mapping of grading criteria to concrete project artifacts and documentation sections.

- Reflection  
  Structured reflection on challenges, lessons learned, and possible improvements.

- Personal Contribution  
  Clear description of individual responsibilities and contributions within the project team.

- Personal Development  
  Overview of personal learning outcomes and skills gained during the project.


7. Database Dump

The SQL dump required to initialize the database is located at:

docs/database/dump-studytask-202601232120.sql

The dump contains the complete database schema and demo data, allowing the application to be tested immediately after setup.


8. Demo Access (Evaluation Only)

For demonstration and evaluation purposes, the following admin credentials are available:

Email: admin@gmail.com  
Password: 12

These credentials are intended for evaluation only.


9. Evaluation Notes

The project is designed to be evaluated using:
- the GitHub repository (source code),
- the provided SQL database dump,
- and the documentation located in the `docs` folder.

All grading criteria communicated during the course are fully addressed through the submitted materials.  
The application is locally runnable, reproducible, and documented in a way that allows evaluation without additional explanation.
