# LiFox API - Personal Productivity Toolbox

Welcome to the LiFox API! This comprehensive application offers a suite of modules to 
enhance personal productivity, ranging from goal tracking to managing contacts, notes, 
and more.

## Features

### Basics

- [ ] **Todo:** Keep track of your personal goals and monitor progress.
- [ ] **Notes:** Organize personal notes by category for quick reference.
- [ ] **Contacts:** Manage your contacts efficiently.
- [ ] **Schedules:** Keep track of recurring tasks and appointments.

### Finance

- [ ] **Payments:** Track expenses and manage finances effortlessly.
- [ ] **Shopping:** Create and manage shopping lists for future purchases.
- [ ] **Price Monitoring:** Monitor prices for particular products and track expenses.
- [ ] **Job:** Monitor work hours, after-hours, and holiday entitlement.

### ~~File Management~~

- [ ] ~~**Images:** Organize photos and multimedia in customizable galleries.~~
- [ ] ~~**Files:** Manage files easily with a user-friendly interface.~~
- [ ] ~~**Video:** Store and view videos with support for popular formats.~~

## Installation

### Clone the repository

```shell
git clone git@github.com:wawoxe/lifox.git
```

### Install dependencies

```shell
composer install
```

### Configure environment variables

```shell
cp .env.example .env
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### Start the development server

```shell
symfony serve
```

Visit https://localhost:8000 in your web browser to access the application.

## Contributing

We welcome contributions from the community! If you'd like to contribute to this project, 
please follow these steps:

1. Fork the repository.
2. Create a new branch (git checkout -b feature/your-feature).
3. Make your changes.
4. Commit your changes (git commit -am 'Add new feature').
5. Push to the branch (git push origin feature/your-feature).
6. Create a new Pull Request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact

For any inquiries or feedback, please contact wawoxe@proton.me.