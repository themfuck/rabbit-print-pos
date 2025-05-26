import React from 'react';
import { Link, Outlet } from 'react-router-dom';

const Layout = () => {
  return (
    <div>
      <header style={{ padding: '1rem', backgroundColor: '#f0f0f0' }}>
        <h1>My E-Commerce App</h1>
        <nav>
          <ul style={{ listStyle: 'none', padding: 0, display: 'flex', gap: '1rem' }}>
            <li>
              <Link to="/">Home</Link>
            </li>
            <li>
              <Link to="/products">Products</Link>
            </li>
            <li>
              <Link to="/login">Login</Link>
            </li>
          </ul>
        </nav>
      </header>
      <main style={{ padding: '1rem' }}>
        <Outlet /> {/* This is where child routes will be rendered */}
      </main>
      <footer style={{ padding: '1rem', backgroundColor: '#f0f0f0', textAlign: 'center', marginTop: '2rem' }}>
        <p>&copy; {new Date().getFullYear()} E-Commerce App</p>
      </footer>
    </div>
  );
};

export default Layout;
