import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { Card, Button, Row, Col, Form, Container } from 'react-bootstrap';

function PetList() {
  const [pets, setPets] = useState([]);
  const [status, setStatus] = useState('');
  const [loading, setLoading] = useState(false);

  const fetchPetsByStatus = (status) => {
    let link;
    if (status.trim() === '') {
      link = 'http://localhost:8000/api/v3/pets';
    } else {
      link = `http://localhost:8000/api/v3/pet/findByStatus?status=${status}`;
    }
    setLoading(true);
    fetch(link)
      .then((res) => res.json())
      .then((data) => {
        setPets(data);
        setLoading(false);
      })
      .catch((error) => {
        console.error('Error fetching pets:', error);
        setLoading(false);
      });
  };

  // handle key enter in filter status input
  const handleKeyDown = (e) => {
    if (e.key === 'Enter') {
      fetchPetsByStatus(status);
    }
  };


  useEffect(() => {
    fetch('http://localhost:8000/api/v3/pets')
      .then((res) => res.json())
      .then((data) => setPets(data))
      .catch((error) => console.error('Error fetching pets:', error));
  }, []);

  const deletePet = (id) => {
    fetch(`http://localhost:8000/api/v3/pet/${id}`, {
      method: 'DELETE',
    })
      .then(() => {
        setPets(pets.filter((pet) => pet.id !== id));
      })
      .catch((error) => console.error('Error deleting pet:', error));
  };

  return (
    <Container>
      {/* Filter pets by status via text input */}
      <Form.Group className="mb-3">
        <Form.Label>Filter by Status</Form.Label>
        <Form.Control
          type="text"
          value={status}
          placeholder="Enter status (e.g. available, dead, sold)"
          onChange={(e) => setStatus(e.target.value)}
          onKeyDown={handleKeyDown}
        />
      </Form.Group>

      {loading ? (
        <p>Loading...</p>
      ) : (
        <Row>
          {pets.map((pet) => (
            <Col md={4} key={pet.id}>
              <Card className="mb-4">
                  <Card.Body>
                  <Card.Title>{pet.name}</Card.Title>
                  <Card.Text>
                    <strong>ID:</strong> {pet.id}<br />
                    <strong>Status:</strong> {pet.status}<br />
                    <strong>Category:</strong> {pet.category}
                  </Card.Text>
                  <Button as={Link} to={`/pet/${pet.id}`} variant="primary" className="me-2">View</Button>
                  <Button as={Link} to={`/edit/${pet.id}`} variant="warning" className="me-2">Edit</Button>
                  <Button onClick={() => deletePet(pet.id)} variant="danger">Delete</Button>
                </Card.Body>
              </Card>
            </Col>
          ))}
        </Row>
      )}
    </Container>
  );
}

export default PetList;
