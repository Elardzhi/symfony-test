### API Authentication:
header: 
```
X-AUTH-TOKEN: 3bf0c1af-5daf-4e1c-8d06-27101bfb1946
```

### SQL Questions:
1. **SQL** - Structured Query Language is used to perform various operations on the data in relational databases. 
2. **RDBMS** - Relational Database Management System is used to store data in a structured format, using rows and columns.
3. **Data Mining** - finding new information (anomalies, patterns and correlations) in a lot of already saved (in a DB) data. That information can be used for prediction.
4. **ERD** - Entity Relationship Diagram shows entities (tables) in a database and relationships between them.
5. Primary key:
   - only one per table (a unique identifier for each row)
   - no NULL values
   - creates clustered index
   Unique key:
   - any number per table (makes sure that values in an indexed column are unique )
   - accepts NULL values
   - creates non-clustered index
6. Images can be stored in databases as a BLOB data type.
7. **Data Warehousing** - is electronic storage of a large amount of information by a business which is designed for query and analysis instead of transaction processing.
8. **Index** - is a key built from one or more columns in the database that speeds up fetching rows from the table.
   - Clustered index (Primary Key) - sorts the data rows in the table on their key values. Required. There can be only one clustered index per table.
   - Secondary index - (Unique, Index, Fulltext, Spatial) - can be used to satisfy queries that only require values from the indexed columns. For more complex queries, it can be used to identify the relevant rows in the table, which are then retrieved through lookups using the clustered index.
9. 6 triggers:
   - BEFORE and AFTER INSERT
   - BEFORE and AFTER UPDATE
   - BEFORE and AFTER DELETE
10. A **Heap** table is a table without a clustered index. Non-clustered indexes can be created. (MEMORY tables in MySQL).
11. **InnoDB**: 
    - supports foreign keys
    - supports transactions
    - locks the current row during querying
    - the data and indexes are cached in memory.
    
    **MyISAM**: 
    - doesn't support foreign keys
    - doesn't support transactions
    - locks the whole table during querying
    - caches only indexes.
