## Simple router for custom framework

This repository provides a lightweight and flexible routing mechanism for custom PHP frameworks. It maps incoming HTTP requests to controller classes by transforming composer namespaces into corresponding directory structures, allowing for intuitive and maintainable route handling.

### Features
- Namespace to Path Conversion – Automatically resolves class names from composer namespaces to file paths, following PSR-4 autoloading standards.
- 100% Test Coverage – Fully tested to ensure reliability and stability.

### How is works

The router analyzes the requested URI and converts it into a fully qualified class name based on predefined routing rules. It then instantiates the corresponding controller and executes its designated method, passing any parameters extracted from the URI.
Also there're tests for this router with 100% coverage.

### Testing
All tests are located in the tests/ directory and can be run with PHPUnit. The suite achieves 100% code coverage, ensuring every line of the router is verified.
