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

In addition to functional implementation, strong emphasis was placed on documentation, reproducibility, and a structured development process.


2. Source Code & Project Access (GitHub)

The complete project source code is available in the following GitHub repository:

https://github.com/oemerue/StudyTask

The repository contains:
- the complete frontend and backend source code,
- all documentation required for setup, usage, and evaluation,
- and the database dump required to run the application locally.

All documents referenced in this README are located within the project repository, primarily in the `docs` directory.


3. Design & Prototyping (Figma)

At the beginning of the project, a visual prototype of the application was created using Figma.
The prototype was used to define the layout, navigation structure, and core user flows before starting the technical implementation.

The Figma prototype served as a design reference throughout development and supported early alignment of frontend structure and user experience.

Figma prototype link:
https://www.figma.com/design/xNRuJ4pEoI9QU0NSKNwm2C/Untitled?node-id=0-1&t=mxnw7tOVuRfGNiSm-1


4. Project / Repository Structure

The project repository is structured as follows:

StudyTask/
- Backend/            PHP backend (API endpoints, authentication, database access)
- Frontend/           HTML, CSS and JavaScript frontend
- docs/               Complete project documentation
  - database/         SQL database dump
  - StudyTask_Setup_Guide.pdf
  - StudyTask_Technical_Documentation.pdf
  - StudyTask_User_Manual.pdf
  - StudyTasks_Process_Documentation.pdf
  - Studytask Project Plan.pdf
- index.html          Entry point of the application

All required documents for understanding, running, and evaluating the project are located inside the `docs` folder.


5. Getting Started (Local Setup)

To run the project locally, please refer to the Installation & Setup Guide located at:

docs/StudyTask_Setup_Guide.pdf

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

All documentation required for the evaluation of the project is located in:

C:\xampp\htdocs\StudyTask\docs\

The documentation is structured as follows:

6.1 Installation & Setup Guide  
File: StudyTask_Setup_Guide.pdf  
Describes how to install and run the application locally, including database setup and startup instructions.

6.2 User Manual  
File: StudyTask_User_Manual.pdf  
Explains how to use the application from an end-user perspective, including registration, login, group management, task handling, calendar view, statistics, and admin functionality.

6.3 Technical Documentation  
File: StudyTask_Technical_Documentation.pdf  
Provides a high-level overview of the system architecture, technologies used, authentication and authorization concepts, and the database model.

6.4 Process, PM, Evidence, Reflection and Personal Sections (StudyTasks_Process_Documentation)

The following required grading components are combined into a single document:

- Project Management documentation (time tracking, sprint reviews, backlog, task management)
- Evidence for all grading criteria
- Reflection
- Personal Contribution
- Personal Development

6.5 Here is the link to Trello, where you can see which tasks have been created for our project.
You can also see who has invested how much time in each individual task! You need an account on Trello to view our board.
The link for Trello is as follows: https://trello.com/invite/b/695a822319cc6d8fe62941b7/ATTI102a9222b6e4ce6d4dc5e7ddf9d4d467A34AFE9C/studytasksorginal

All of these elements are documented in the following file:

C:\xampp\htdocs\StudyTask\docs\StudyTasks_Process_Documentation.pdf

This document provides a complete overview of the development process, project organization, evaluation evidence, individual contributions, and learning outcomes.


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
- the SQL database dump,
- and the documentation located in the `docs` folder.

All grading criteria communicated during the course are fully addressed through the submitted materials.
The application is locally runnable, reproducible, and documented in a way that allows evaluation without additional explanation.
