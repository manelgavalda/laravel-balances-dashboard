# Dashboard for Token Monitoring with Laravel

**Live Demo**: [View Demo](https://laravel-balances-dashboard.vercel.app)

<img src="https://raw.githubusercontent.com/manelgavalda/laravel-balances-dashboard/main/public/images/dashboard.png">

## Overview

This GitHub repository hosts a dashboard built using the Laravel framework. The primary purpose of this dashboard is to monitor tokens stored in a Supabase database. It leverages the power of Chart.js to visually represent data and utilizes Ether.js and the CoinGecko API for token data retrieval and integration. The application has undergone rigorous testing with Pest and is efficiently hosted on Vercel for seamless deployment.

## Features

- **Token Monitoring**: Gain insights into tokens stored within a Supabase database.
- **Data Visualization**: Utilize Chart.js to create visually appealing and informative charts.
- **Data Integration**: Fetch token data effortlessly using Ether.js and the CoinGecko API.
- **Robust Testing**: Rigorously tested using Pest to ensure reliability.
- **Effortless Deployment**: Seamlessly hosted on Vercel for a hassle-free deployment process.

## Usage

To deploy and utilize this dashboard, follow the steps below:

1. **Clone the Repository**: Clone this repository to your local environment using the following command:

   ```bash
   git clone https://github.com/manelgavalda/laravel-balances-dashboard.git
   ```

2. **Install Dependencies**: Navigate to the project directory and install the required dependencies:

   ```bash
   composer install
   npm install
   ```

3. **Configuration**: Configure your Supabase credentials in the respective configuration files. It's important to note that the CoinGecko API and Ether.js are utilized in the service responsible for saving data to the Supabase database, which is not a part of this repository. Ensure that the service handling data storage is correctly configured with the necessary CoinGecko API and Ether.js credentials for seamless data integration.

4. **Database Setup**: Set up your Supabase database and ensure that it is properly configured in the Laravel application.

5. **Testing**: Run the Pest test suite to verify the functionality of the application:

   ```bash
   php artisan test
   ```

6. **Deployment**: Deploy the application to Vercel or your preferred hosting platform.

## License

This project is licensed under the [MIT License](LICENSE).

## Acknowledgments

- [Vercel - The platform for hosting the dashboard](https://vercel.com).
- [Supabase - The database platform for storing token data](https://supabase.com).
- [Laravel - The PHP framework that powers this application](https://laravel.com).
- [Pest - The testing framework used for quality assurance](https://pestphp.com).
- [Chart.js - The JavaScript library used for data visualization](https://chartjs.org).
- [Ether.js - The JavaScript library for Ethereum interactions](https://ethers.org).
- [CoinGecko - The API for fetching token-related information](https://www.coingecko.com/en/api).

## Contact

For inquiries or support, please contact [Manel Gavalda](mailto:manelgavalda1@gmail.com).

---

[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

This README is part of the [Laravel Balances Dashboard](https://github.com/manelgavalda/laravel-balances-dashboard) project.
