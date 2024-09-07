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

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prevData) => ({
      ...prevData,
      [name]: value,
    }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    fetch('http://localhost:8000/api/v3/pet', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(formData)

    })
      .then(() => {
        navigate('/'); // Po úspešnom pridaní zvieratka sa vráť späť na hlavnú stránku
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

        <Button type="submit" variant="success">
          Add Pet
        </Button>
      </Form>
    </Container>
  );
}

export default AddPet;
