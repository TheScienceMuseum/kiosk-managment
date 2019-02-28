import AdminLayout from '../views/layouts/AdminLayout';
import EditorLayout from "../views/layouts/EditorLayout";

export default [
    { path: "/admin", name: "Admin", component: AdminLayout },
    { path: "/dashboard", name: "Dashboard", component: AdminLayout },
    { path: "/editor/:package_id([0-9]+)/version/:package_version_id([0-9]+)", name: "Package Editor", component: EditorLayout },
    { path: "/", redirect: true, pathTo: "/dashboard", name: "Dashboard" },
];
