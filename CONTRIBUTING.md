# Contributing to ZH-Zephyr

Thank you for considering contributing to ZH-Zephyr! We welcome community contributions to help make this zero-dependency micro-framework even better.

## Code of Conduct

By participating in this project, you agree to maintain a professional, respectful, and welcoming environment for everyone.

## How Can I Contribute?

### 1. Reporting Bugs
* Check the GitHub Issues tab to ensure the bug hasn't already been reported.
* Open a new issue containing a clear title, descriptive summary, steps to reproduce, and the expected vs actual behavior.

### 2. Suggesting Features
* Open an issue with the prefix `[Feature Request]` in the title.
* Explain the use case and why this feature would benefit shared-hosting developers.

### 3. Submitting Pull Requests
* Fork the repository and create your branch from `main`.
* Ensure your code adheres to clean PHP standards.
* Keep your code dependency-free (Pure PHP only).
* Write clean, self-explanatory commit messages.
* Open a Pull Request (PR) targeting the `main` branch.

## Development Principles
* **Zero Dependencies:** No composer packages allowed in the core layer.
* **Shared Hosting First:** The framework must remain lightweight and performant on traditional cPanel environments.
* **Backward Compatibility:** Avoid breaking changes to existing routing or model structures.
