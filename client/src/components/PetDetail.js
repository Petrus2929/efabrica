import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { Card, Button } from 'react-bootstrap';

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
    <Card>
      <Card.Body>
        <Card.Title>{pet.name}</Card.Title>
        <Card.Text>
          <strong>ID:</strong> {pet.id}<br />
          <strong>Status:</strong> {pet.status}<br />
          <strong>Category:</strong> {pet.category}
        </Card.Text>
        <Button as={Link} to="/" variant="primary">Back to List</Button>
      </Card.Body>
    </Card>
  );
}

export default PetDetail;
