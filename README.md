# Kiosk Management System

## Deployment

### Local

Setting up this repo locally just involves setting up [homestead](https://laravel.com/docs/5.7/homestead) in your home 
directory and configuring it appropriately, something like the following should work nicely.

```yaml
folders:
 - map: ~/Code
   to: /home/vagrant/Code
sites:
 - map: kiosk-manager.test
   to: /home/vagrant/Code/Clients/ScienceMuseum/Kiosk-Manager/public
databases:
 - homestead
 - kiosk_manager
```

You'll find that the default `.env.example` file is set up to support the above Homestead configuration.
