# Promtopolis

School Project for [2IMD](https://www.thomasmore.be/en/ba-xd)

## Table of Contents

- [About](#about)
- [Installation](#installation)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)
- [Acknowledgements](#acknowledgements)

## About

Promtopolis is a platform where you can share prompts trained on AI models like Dall-E. Other users can purchase these prompts using credits. You can earn credits by contributing to the community, such as commenting on posts or having your prompts approved. Our goal is to foster a thriving community and facilitate the sharing of trained AI models with others on the internet.

## Installation

To install Promtopolis on your own system, follow these steps:

1. Type `composer install` in your terminal to install the necessary dependencies from the `composer.json` file. The required dependencies are `sengrid` and `cloudinary`.
2. Type `composer dump-autoload` in your terminal to update the autoload with the newly added namespaces.
3. Add a `config` folder into `src/Promptopolis/Framework` and place a `config.ini` file inside it.
4. In the `config.ini` file, provide your database host, name, user, and password.
5. In the `config.ini` file, add your SendGrid API key.
6. In the `config.ini` file, add your Cloudinary name, API key, and secret.
7. Run the website.

You can access the actual website at [https://tibomertens.be/promptopolis/](https://tibomertens.be/promptopolis/).

## Contributing

Contributions to this repository are not allowed at the moment.

## Contact

For any inquiries or questions, you can reach out to me at <tibomertens25@gmail.com>.

## Acknowledgements

This project drew inspiration from [PromptBase](https://promptbase.com).
