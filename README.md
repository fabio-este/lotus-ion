# Lotus Ion Homepage

## General

*All Classes, Config, Assets and Templates can be edited and extended in the packages/site-package

## Prerequisites

* PHP 8.1
* MySql
* Apache2 Webserver
* Composer (https://getcomposer.org/download/)
* Yarn (https://yarnpkg.com/getting-started)

### Local Setup

* DDEV [LOCAL DEVELOPMENT] (https://ddev.readthedocs.io/en/stable/users/install/)
* Install via local_install.sh (!!! DON'T USE ON SERVER !!!)
* import DB

### Launch Local Instance

* ddev start
* ddev auth ssh [authorizes DDEV Container]


### Build

* via Yarn

For Development
```
ddev auth ssh && ddev ssh
yarn watch
```

Before Committing to CVS & Deployment
```
ddev auth ssh && ddev ssh
yarn watch
```


### Deployment

* via Deployer

```
ddev auth ssh && ddev ssh
dep deploy [-vvv]   
```

## License

GPL-2.0 or later
