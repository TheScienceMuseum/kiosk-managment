import DashboardLayout from "../views/layouts/DashboardLayout";
import AdminLayout from '../views/layouts/AdminLayout';

export default [
    { path: "/admin", name: "Admin", component: AdminLayout },
    { path: "/dashboard", name: "Dashboard", component: DashboardLayout },
    { path: "/", redirect: true, pathTo: "/dashboard", name: "Dashboard" },
];
