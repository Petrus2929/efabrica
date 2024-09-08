import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Form, Button, Container } from 'react-bootstrap';

function AddPet() {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    name: '',
    status: '',
    category: '',
  });
  const [image, setImage] = useState(null);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prevData) => ({
      ...prevData,
      [name]: value,
    }));
  };

  const handleImageChange = (e) => {
    setImage(e.target.files[0]);
  };


  const handleSubmit = (e) => {
    e.preventDefault();

    const data = new FormData();
    data.append('name', formData.name);
    data.append('status', formData.status);
    data.append('category', formData.category);
    if (image) {
      data.append('file', image);
    }

    fetch('http://localhost:8000/api/v3/pet', {
      method: 'POST',
      body: data
    })
      .then(() => {
        navigate('/');
      })
      .catch((error) => console.error('Error adding pet:', error));
  };

  return (
    <Container className="mt-4">
      <h1>Add New Pet</h1>
      <Form onSubmit={handleSubmit}>
        <Form.Group className="mb-3">
          <Form.Label>Name</Form.Label>
          <Form.Control
            type="text"
            name="name"
            value={formData.name}
            onChange={handleChange}
            required
          />
        </Form.Group>

        <Form.Group className="mb-3">
          <Form.Label>Status</Form.Label>
          <Form.Control
            type="text"
            name="status"
            value={formData.status}
            onChange={handleChange}
            required
          />
        </Form.Group>

        <Form.Group className="mb-3">
          <Form.Label>Category</Form.Label>
          <Form.Control
            type="text"
            name="category"
            value={formData.category}
            onChange={handleChange}
            required
          />
        </Form.Group>

        <Form.Group className="mb-3">
          <Form.Label>Image</Form.Label>
          <Form.Control
            type="file"
            name="image"
            accept="image/*"
            onChange={handleImageChange}
          />
        </Form.Group>

        <Button type="submit" variant="success">
          Add Pet
        </Button>
      </Form>
    </Container>
  );
}

export default AddPet;
