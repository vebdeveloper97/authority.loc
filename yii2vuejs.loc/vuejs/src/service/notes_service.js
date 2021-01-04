import axios from 'axios';

// eslint-disable-next-line no-unused-vars
const notesService = {
    results: null,
    async getData(){
        this.results = await axios.get('http://localhost:8080/api/note');
        return this.results;
    }
}

export default notesService;