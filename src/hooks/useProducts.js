import { useState, useEffect, useCallback } from 'react';
import apiClient from '../services/api'; // Corrected path

const useProducts = () => {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const fetchProducts = useCallback(async () => {
    setLoading(true);
    setError(null);
    try {
      // The backend API for products should return data in a structure that
      // includes pagination, typically like: { data: [], links: {}, meta: {} }
      // We are interested in the 'data' array from the response.
      const response = await apiClient.get('/products');
      setProducts(response.data.data || []); // Assuming products are in response.data.data
                                          // Adjust if your API structure is different
    } catch (err) {
      setError(err);
      console.error("Error fetching products:", err);
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    fetchProducts();
  }, [fetchProducts]);

  return { products, loading, error, refetchProducts: fetchProducts };
};

export default useProducts;
