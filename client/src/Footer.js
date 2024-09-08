import React from 'react';
import { Container } from 'react-bootstrap';

function Footer() {
  return (
    <footer className="bg-dark text-white text-center py-3">
      <Container>
        <p>© 2024 Pet Shop Application for eFabrica</p>
        <p>Created by: Daniel Petrus</p> 
      </Container>
    </footer>
  );
}

export default Footer;