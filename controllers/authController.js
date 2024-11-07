const { User } = require('../models');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');

// Controlador para el registro de usuarios
const register = async (req, res) => {
    try {
        const { name, email, password } = req.body;

        // Verificar si el usuario ya existe
        const userExists = await User.findOne({ where: { email } });
        if (userExists) {
            return res.status(400).json({ message: 'El usuario ya existe' });
        }

        // Encriptar la contraseña
        const salt = await bcrypt.genSalt(10);
        const hashedPassword = await bcrypt.hash(password, salt);

        // Crear nuevo usuario
        const user = await User.create({
            name,
            email,
            password: hashedPassword,
            active: true // Asumiendo que tenemos este campo en la tabla
        });

        // Generar JWT
        const token = jwt.sign(
            { id: user.id },
            process.env.JWT_SECRET,
            { expiresIn: '1d' }
        );

        res.status(201).json({
            message: 'Usuario registrado exitosamente',
            token,
            user: {
                id: user.id,
                name: user.name,
                email: user.email
            }
        });

    } catch (error) {
        console.error('Error en registro:', error);
        res.status(500).json({ message: 'Error en el servidor', error: error.message });
    }
};

// Controlador para el login de usuarios
const login = async (req, res) => {
    try {
        const { email, password } = req.body;

        // Verificar si el usuario existe
        const user = await User.findOne({ 
            where: { 
                email,
                active: true // Solo usuarios activos pueden hacer login
            } 
        });

        if (!user) {
            return res.status(400).json({ message: 'Usuario no encontrado o inactivo' });
        }

        // Verificar contraseña
        const isMatch = await bcrypt.compare(password, user.password);
        if (!isMatch) {
            return res.status(400).json({ message: 'Credenciales inválidas' });
        }

        // Generar JWT
        const token = jwt.sign(
            { id: user.id },
            process.env.JWT_SECRET,
            { expiresIn: '1d' }
        );

        res.json({
            message: 'Login exitoso',
            token,
            user: {
                id: user.id,
                name: user.name,
                email: user.email
            }
        });

    } catch (error) {
        console.error('Error en login:', error);
        res.status(500).json({ message: 'Error en el servidor', error: error.message });
    }
};

module.exports = {
    register,
    login
}; 