import React from 'react';
import { BrowserRouter as Router, Route, Routes, Link } from 'react-router-dom';
import PetList from './components/PetList';
import PetDetail from './components/PetDetail';
import EditPet from './components/EditPet';
import AddPet from './components/AddPet';
import { Navbar, Container, Nav, Row, Col } from 'react-bootstrap';
import petImage from './assets/pet_shop.jpeg';
import Footer from './Footer'; 

function App() {
  return (
    <Router>
      <Navbar bg="dark" variant="dark" expand="lg">
        <Container>
          <Navbar.Brand as={Link} to="/">Pet Store by Daniel Petrus</Navbar.Brand>
          <Nav className="me-auto">
            <Nav.Link as={Link} to="/">Home</Nav.Link>
            <Nav.Link as={Link} to="/add">Add Pet</Nav.Link>
          </Nav>
          <Row className="justify-content-right">
            <Col className="text-center">
              <img src={petImage} alt="Pet" style={{ maxWidth: '30%', height: 'auto' }} />
            </Col>
          </Row>
        </Container>
      </Navbar>

      <Container className="mt-4">
        <Routes>
          <Route path="/" element={<PetList />} />
          <Route path="/pet/:id" element={<PetDetail />} />
          <Route path="/edit/:id" element={<EditPet />} />
          <Route path="/add" element={<AddPet />} />
        </Routes>
      </Container>
      <Footer /> 
    </Router>
  );
}

export default App;
