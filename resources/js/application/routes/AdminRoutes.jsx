import React from 'react';
import User from '../../helpers/User';
import Resource from "../components/Resource/Resource";

const adminRoutes = [];

if (User.can('view all users')) {
    adminRoutes.push({
        path: "/admin/users",
        name: "Users",
        icon: ["fal", "users"],
        component: (props) => {
            return (
                <Resource resourceName={'user'} {...props} />
            )
        },
    });
}

if (User.can('view all kiosks')) {
    adminRoutes.push({
        path: "/admin/kiosks",
        name: "Kiosks",
        icon: ["fal", "desktop-alt"],
        component: (props) => {
            return (
                <Resource resourceName={'kiosk'} {...props} />
            )
        },
    });
}

if (User.can('view all packages')) {
    adminRoutes.push({
        path: "/admin/packages",
        name: "Package",
        icon: ["fal", "box"],
        component: (props) => {
            return (
                <Resource resourceName={'package'} {...props} />
            )
        },
    });
}


export default adminRoutes;