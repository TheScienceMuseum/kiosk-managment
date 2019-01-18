import React from 'react';
import User from '../../helpers/User';
import Resource from "../components/Resource/Resource";

const adminRoutes = [];

if (User.can('view all users')) {
    adminRoutes.push({
        show_in_menu: true,
        path: "/admin/users",
        name: "Users",
        icon: ["fal", "users"],
        component: (props) => {
            return (
                <Resource resourceName={'user'}
                          path={"/admin/users"}
                          {...props} />
            )
        },
    });
}

if (User.can('view all kiosks')) {
    adminRoutes.push({
        show_in_menu: true,
        path: "/admin/kiosks",
        name: "Kiosks",
        icon: ["fal", "desktop-alt"],
        component: (props) => {
            return (
                <Resource resourceName={'kiosk'}
                          path={"/admin/kiosks"}
                          {...props} />
            )
        },
    });
}

if (User.can('view all packages')) {
    adminRoutes.push({
        show_in_menu: true,
        path: "/admin/packages",
        name: "Package",
        icon: ["fal", "box"],
        component: (props) => {
            return (
                <Resource resourceName={'package'}
                          path={"/admin/packages"}
                          {...props} />
            )
        },
    });
}


export default adminRoutes;