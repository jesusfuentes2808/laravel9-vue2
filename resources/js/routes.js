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
                component: require('./component/WelcomeComponent').default
            }
        ]
    },
    {
        path: "/admin",
        component: require('./component/layout/AdminComponent').default,
        children: [
            {
                path: "example",
                component: require('./component/ExampleComponent').default,
                name: 'admin.example'
            },
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
