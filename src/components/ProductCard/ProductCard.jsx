import React from 'react';
import { Link } from 'react-router-dom';

const ProductCard = ({ product }) => {
  if (!product) {
    return null; // Or some placeholder for an empty product
  }

  return (
    <div style={{
      border: '1px solid #ddd',
      borderRadius: '8px',
      padding: '1rem',
      margin: '0.5rem',
      width: '250px', // Fixed width, adjust as needed for grid
      boxShadow: '0 2px 4px rgba(0,0,0,0.1)'
    }}>
      {product.image_url && (
        <img
          src={product.image_url}
          alt={product.name}
          style={{ width: '100%', height: '150px', objectFit: 'cover', borderRadius: '4px' }}
        />
      )}
      {!product.image_url && (
        <div style={{
          width: '100%',
          height: '150px',
          backgroundColor: '#eee',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          borderRadius: '4px',
          color: '#aaa'
        }}>
          No Image
        </div>
      )}
      <h3 style={{ marginTop: '0.5rem', fontSize: '1.2rem' }}>
        <Link to={`/products/${product.id}`} style={{ textDecoration: 'none', color: '#333' }}>
          {product.name || 'Unnamed Product'}
        </Link>
      </h3>
      <p style={{ fontSize: '1rem', color: '#555' }}>
        Price: ${product.price ? parseFloat(product.price).toFixed(2) : 'N/A'}
      </p>
      <p style={{ fontSize: '0.9rem', color: product.stock > 0 ? '#28a745' : '#dc3545' }}>
        Stock: {product.stock !== undefined ? product.stock : 'N/A'}
      </p>
      <Link to={`/products/${product.id}`} style={{
         display: 'inline-block',
         marginTop: '0.5rem',
         padding: '0.5rem 1rem',
         backgroundColor: '#007bff',
         color: 'white',
         textDecoration: 'none',
         borderRadius: '4px'
      }}>
        View Details
      </Link>
    </div>
  );
};

export default ProductCard;
