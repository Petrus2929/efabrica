import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { Card, Button, Row, Col } from 'react-bootstrap';

function PetList() {
  const [pets, setPets] = useState([]);

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
    <div>
      <h1 className="text-center mb-4">Pet List</h1>
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
    </div>
  );
}

export default PetList;
