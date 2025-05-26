import React from 'react';
import { Routes, Route } from 'react-router-dom';
import Layout from './components/Layout/Layout';
import HomePage from './pages/HomePage';
import LoginPage from './pages/LoginPage';
import ProductsPage from './pages/ProductsPage';
import NotFoundPage from './pages/NotFoundPage';

// If you plan to fetch CSRF cookie on app load:
// import { useEffect } from 'react';
// import { getCsrfCookie } from './services/api'; // Assuming you export it from api.js

function App() {
  // Example: Fetch CSRF cookie on initial app load
  // useEffect(() => {
  //   getCsrfCookie();
  // }, []);

  return (
    <Routes>
      <Route path="/" element={<Layout />}>
        <Route index element={<HomePage />} />
        <Route path="products" element={<ProductsPage />} />
        <Route path="login" element={<LoginPage />} />
        <Route path="*" element={<NotFoundPage />} />
      </Route>
    </Routes>
  );
}

export default App;
