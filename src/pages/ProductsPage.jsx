import React from 'react';
import useProducts from '../hooks/useProducts'; // Corrected path
import ProductCard from '../components/ProductCard/ProductCard'; // Corrected path

const ProductsPage = () => {
  const { products, loading, error, refetchProducts } = useProducts();

  if (loading) {
    return <p>Loading products...</p>;
  }

  if (error) {
    return (
      <div>
        <p>Error fetching products: {error.message || 'Unknown error'}</p>
        <button onClick={refetchProducts}>Try Again</button>
      </div>
    );
  }

  if (!products || products.length === 0) {
    return <p>No products found.</p>;
  }

  return (
    <div>
      <h2>Products</h2>
      <button onClick={refetchProducts} style={{ marginBottom: '1rem' }}>
        Refresh Products
      </button>
      <div style={{
        display: 'flex',
        flexWrap: 'wrap',
        gap: '1rem', // Space between cards
        justifyContent: 'center' // Center cards in the container
      }}>
        {products.map(product => (
          <ProductCard key={product.id} product={product} />
        ))}
      </div>
    </div>
  );
};

export default ProductsPage;
