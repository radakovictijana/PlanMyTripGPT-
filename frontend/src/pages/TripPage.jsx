import React from 'react'
import Trip from '../models/Trip'
import { useState,useEffect} from 'react'
import TextField from '../components/shared/TextField';
import Footer from '../components/shared/Footer';
import axios from 'axios';

export default function TripPage() {
  const [trips, setTravels] = useState([]);
  const [searchTerm, setSearchTerm] = useState("");
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(5);
  const [errors, setError] = useState(null);
  const [startYear, setStartYear] = useState('');
  const [endYear, setEndYear] = useState('');
  const [hasGuide, setHasGuide] = useState('');
  const [sortOrder, setSortOrder] = useState('asc');

  useEffect(() => {
    fetchUsers();
  }, [currentPage, searchTerm, startYear, endYear, hasGuide, sortOrder]);

  const fetchUsers = async () => {
    const token = localStorage.getItem("auth_token");
    const userId = localStorage.getItem("user_id");

    try {
      const response = await axios.get(`api/users/${userId}/travels`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
        params: {
          page: currentPage,
          per_page: 5,
          start_year: startYear,
          end_year: endYear,
          guide: hasGuide,
          sort_order: sortOrder
        }
      });

      setTotalPages(response.data.last_page);
      setTravels(response.data.data);
    } catch (err) {
      setError("Failed to fetch travels.");
    }
  };

  function handlePreviousPage() {
    setCurrentPage(prevPage => Math.max(prevPage - 1, 1));
  }

  function handleNextPage() {
    setCurrentPage(prevPage => Math.min(prevPage + 1, totalPages));
  }

  function handleSearch(e) {
    setSearchTerm(e.target.value.toLowerCase());
  }

  function handleFilterChange(e) {
    const { name, value } = e.target;
    if (name === 'start_year') setStartYear(value);
    if (name === 'end_year') setEndYear(value);
    if (name === 'guide') setHasGuide(value);
  }

  function handleYearChange(e) {
    setStartYear(e.target.value);
  }

  function handleSortChange(e) {
    setSortOrder(e.target.value);
  }

  const years = Array.from({ length: 10 }, (_, i) => new Date().getFullYear() - i);

  const filteredDate = trips.filter(t => {
    return t.destination.toLowerCase().includes(searchTerm);
  });

  const sortedTrips = [...filteredDate].sort((a, b) => {
    const nameA = a.destination.toLowerCase();
    const nameB = b.destination.toLowerCase();
    if (sortOrder === 'asc') {
      return nameA.localeCompare(nameB);
    } else {
      return nameB.localeCompare(nameA);
    }
  });

  return (
    <>
      <div className='pocetna-page'>
        <div className='search'>
          <p style={{ marginRight: '20px' }}>Search:</p>
          <TextField
            type="text"
            name="search"
            id="search_id"
            placeholder='Search...'
            value={searchTerm}
            onChange={handleSearch}
          />
        </div>

        <div className='filters'>
          <label>
            Start Year:
            <input type="number" name="start_year" value={startYear} onChange={handleFilterChange} />
          </label>
          <label>
            End Year:
            <input type="number" name="end_year" value={endYear} onChange={handleFilterChange} />
          </label>
          <label>
            Has Guide:
            <select name="guide" value={hasGuide} onChange={handleFilterChange}>
              <option value="">All</option>
              <option value="true">Yes</option>
              <option value="false">No</option>
            </select>
          </label>
          <label>
            Sort by Name:
            <select value={sortOrder} onChange={handleSortChange}>
              <option value="asc">Ascending</option>
              <option value="desc">Descending</option>
            </select>
          </label>
        </div>

        {sortedTrips.length ? sortedTrips.map(t =>
          <Trip key={t.id} trip={{id: t.id, destination: t.destination, start_date: t.start_date, end_date: t.end_date,picture:t.picture }} />
        ) : (
          trips.map(t => (
            <Trip key={t.id} trip={{ id: t.id,destination: t.destination, start_date: t.start_date, end_date: t.end_date,picture:t.picture }} />
          ))
        )}

        <div >
          <button onClick={handlePreviousPage} disabled={currentPage === 1} className='pagination-button'>Previous</button>
          <button onClick={handleNextPage} disabled={currentPage === totalPages} className='pagination-button'>Next</button>
        </div>

        <Footer />
      </div>
    </>
  );
}