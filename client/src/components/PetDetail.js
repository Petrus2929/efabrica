import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { Card, Button, Row, Col, Container } from 'react-bootstrap';

function PetDetail() {
  const { id } = useParams();
  const [pet, setPet] = useState(null);

  useEffect(() => {
    fetch(`http://localhost:8000/api/v3/pet/${id}`)
      .then((res) => res.json())
      .then((data) => setPet(data))
      .catch((error) => console.error('Error fetching pet detail:', error));
  }, [id]);

  if (!pet) {
    return <p>Loading...</p>;
  }

  return (
    <Container className="mt-4">
      <Card>
        <Row>
          <Col md={6}>
            <Card.Body>
              <Card.Title>{pet.name}</Card.Title>
              <Card.Text>
                <strong>ID:</strong> {pet.id}<br />
                <strong>Status:</strong> {pet.status}<br />
                <strong>Category:</strong> {pet.category}
              </Card.Text>
            </Card.Body>
            <Button as={Link} to="/" variant="primary">Back to List</Button>
          </Col>

          <Col md={6}>
            <Card.Img
              src={`http://localhost:8000/home/show?image=${pet.imageName}`}
              style={{ maxHeight: '200px', maxWidth: '200px', objectFit: 'cover' }}
            />
          </Col>
        </Row>
      </Card>
    </Container>
  );
}

export default PetDetail;
