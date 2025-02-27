import React from 'react'
import { useState,useEffect } from 'react'
import axios from 'axios';
import {Link} from 'react-router-dom'

const roleMap = {
  0: "user",
  1: "admin",
  2: "guide"
};

function UsersPage() {
  const [users, setUsers] = useState([]);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchUsers = async () => {
      const token = localStorage.getItem("auth_token");
      console.log('Token:', token);
      
      try {
        const response = await axios.get("api/users", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        console.log('API Response:', response); 
        console.log('Fetched Users:', response.data.data);
        setUsers(response.data.data); 
      } catch (err) {
        console.error('Error fetching users:', err.response || err.message);
        setError('Failed to fetch users');
      }
    };

    fetchUsers();
  }, []);
  useEffect(() => {
    console.log('Updated users state:', users);
  }, [users]);

  return (
    <div className='container'>
      
      {error && <p>{error}</p>} {/* Display error message */}
      {users.length === 0 && !error && <p>No users found</p>}
      <div>
        <table className='table'>
          <thead>
            <tr>
              <th>User ID</th>
              <th>User Name</th>
              <th>User Email</th>
              <th>User Role</th>
            </tr>
          </thead>
          <tbody>
            {users.length > 0 ? (
              users.map(user => (
                <tr key={user.id}>
                  <td>{user.id}</td>
                  <td>{user.name}</td>
                  <td>{user.email}</td>
                  <td>{roleMap[user.role]}</td>
                </tr>
              ))
            ) : (
              <tr>
                <td colSpan="5">No users found</td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}

export default UsersPage;