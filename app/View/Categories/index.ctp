<!-- File: /app/views/categories/index.ctp -->
 
<h1>Categories</h1>
<table>
    <tr>
        <th>Id</th>
        <th>Title</th>
    </tr>
 
    <?php foreach ($categories as $category): ?>
    <tr>
        <td><?php echo $category['Category']['id']; ?></td>
        <td><?php echo $category['Category']['name']; ?>
            <?php //echo $html->link($category['Category']['name'], array('controller' => 'categories', 'action' => 'view', $category['Category']['id'])); ?>
        </td>
    </tr>
    <?php endforeach; ?>
 
</table>