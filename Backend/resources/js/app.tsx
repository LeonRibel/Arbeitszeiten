import React from 'react'
import ReactDOM from 'react-dom/client'
import Login from './components/Login'
import './bootstrap'
import '../css/app.css'

ReactDOM.createRoot(document.getElementById('app')!).render(
    <React.StrictMode>
        <Login />
    </React.StrictMode>
)
