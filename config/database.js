module.exports = {
    development: {
        username: process.env.DB_USER || 'your_user',
        password: process.env.DB_PASSWORD || 'your_password',
        database: process.env.DB_NAME || 'your_database',
        host: process.env.DB_HOST || 'localhost',
        dialect: 'mysql',
        port: process.env.DB_PORT || 3306
    },
    // Puedes agregar configuraciones para test y production aquí
}; 