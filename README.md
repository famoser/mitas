# Mitas

[![PHP Composer](https://github.com/famoser/mitas/actions/workflows/php.yml/badge.svg)](https://github.com/famoser/mitas/actions/workflows/php.yml)
[![Node.js Encore](https://github.com/famoser/mitas/actions/workflows/node.js.yml/badge.svg)](https://github.com/famoser/mitas/actions/workflows/node.js.yml)

This tool helps a small care institution organize shifts for their employees. Each month, every employee receives an email with a link to declare their requests for the next month (e.g. a free weekend to attend a private event).

## Workflow

Every period, administrators create an era.

![Table with the created eras, and a button to create a new era.](doc/index.png "Administrator area with eras")

Per era, the administrators add employees. Once this is done, the administrators can send an email to all employees to ask for participation until some deadline.

![Table with the employees of the era, and a button to add an employee.](doc/era.png "Administrator area with employees for some era")

The employee receives the email which contains a special link. Using this link, the employee can enter their requests for the era, using some semi-structured form.

![Form with fields for special requests and comments for the corresponding era.](doc/reply.png "Employee form to enter requests for the corresponding era")

When the deadline of the era closes, the employees can no longer change their requests. The administrator may then download an export of all the received answers.