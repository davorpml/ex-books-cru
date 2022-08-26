## Ewa Books CRU
<ul>
    <li>Install dependencies with composer</li>
    <li>Endpoint is (in) base directory (<code>index.php</code>)</li>
    <li>Implemented actions are:
        <ul>
            <li>Get book details: HTTP GET - <code>book_id</code> query param</li>
            <li>Create book row: HTTP POST - payload with keys <code>name, author</code></li>
            <li>Update book details: HTTP PUT - payload with keys <code>name, author</code> and query param <code>book_id</code></li>
        </ul>
    </li>
    <li>Can be tested with Postman (if there is no DB connection there are suitable generated responses)</li>
    <li>Import database from <code>database.sql</code> file</li>
    <li>Create <code>.env</code> file and put DB credentials like in <code>.env.example</code></li>
</ul>