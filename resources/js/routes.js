const routes = [
    {
        path: "",
        component: require('./component/layout/LoginComponent').default,
        children: [
            {
                path: 'login',
                name: 'login',
                component: require('./component/LoginComponent').default
            },
            {
                path: 'register',
                name: 'register',
                component: require('./component/RegisterComponent').default
            },
            {
                path: '',
                name: 'index',
                redirect: "/login",
                //component: require('./component/LoginComponent').default
            }
        ]
    },
    {
        path: "/admin",
        component: require('./component/layout/AdminComponent').default,
        children: [
            {
                path: "invoice",
                component: require('./component/admin/InvoiceComponent').default,
                name: 'admin.invoice'
            },
            {
                path: "invoice/create",
                component: require('./component/admin/InvoiceCreateComponent').default,
                name: 'admin.invoice.create'
            },
            {
                path: "invoice/edit/:id",
                component: require('./component/admin/InvoiceUpdateComponent').default,
                name: 'admin.invoice.edit'
            }
        ]
    }
];

export default routes;
