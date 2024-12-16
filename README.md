# ✨ Lead Management System ✨

Welcome to the **Lead Management System**! 🚀 This project is your go-to solution for handling leads efficiently and elegantly. Built with the power of **Slim Framework 4** and a clean MVC architecture, it’s designed to be lightweight, robust, and easy to extend.

## 🌟 Key Highlights
- 🗂 **Organize Leads**: Effortlessly collect and manage leads with essential information.
- ✅ **Data Validation**: Ensure all inputs are clean and valid before storing them.
- 🔗 **External Notifications**: Automatically notify an external system whenever a lead is submitted.
- 📜 **Logging Done Right**: Track application events and errors with an integrated logging system.
- 🐳 **Containerized with Docker**: Spin up the app effortlessly using Docker for a seamless experience.

Whether you're a developer looking for a well-structured project or a business owner in need of a tool to manage leads, this project is crafted with care and precision to meet your needs. 🎯

Let’s dive in and see what this system can do for you! 🌐✨

---

## Description

This project is a **Lead Management System** where users can submit leads through a form. Each lead is stored in a MySQL database and a notification is sent to an external system. The application is built with **Slim Framework 4** and follows an MVC architecture.

### Features
- Submit leads with fields for name, email, phone, and source.
- Validate lead data before storing it in the database.
- Notify an external system with lead details.
- Robust error handling with logging.
- Containerized with Docker for easy deployment.

---

## Requirements

- Docker and Docker Compose installed.
- Composer installed locally for dependency management.
- PHP 8.0 or higher if running locally.

---

## Installation and Setup

### 1. Clone the Repository
```bash
git clone https://github.com/Alecnm/lead-management-system.git
cd lead-management-system
 ```
### 2. Copy the .env.example file and customize it:
```bash
cp .env.example .env
 ```
### 3. Run the Application with Docker:
```bash
docker-compose up --build
 ```
### 4. Run Migrations:
```bash
docker exec -it lead-management-app vendor/bin/phinx migrate
 ```
### 5. Access the Application:
```bash
Open http://localhost:8000 in your browser to use the application
 ```

#### User usage
- Create a lead
- You will be redirected to a success page
- repeat

During this process it will be trying to notify that the creation was successful to the provided endpoint. And saving the logs with the error messages in the /logs folder.