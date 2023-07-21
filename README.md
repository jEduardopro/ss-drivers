<h1 align="center">Platform Science Code Exercise</h1>

## Setup Installation

### Clone project
```
git clone https://github.com/jEduardopro/ss-drivers.git

cd ss-drivers
```

### Before run application
- Get copy the .env.example to .env

### With Docker
```
docker build . -t ss-drivers
```

```
docker run ss-drivers
```

#### Get container id
```
docker container ps
```
```
docker exec -it {container id} bash
```

#### Run command
```
artisan assign-destinations
```

#### You can use the following paths for command
```
/public/addresses.txt
/public/drivers.txt
```

### Without Docker
```
composer install
```

```
artisan assign-destinations
```
