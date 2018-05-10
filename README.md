# Mapas Culturais Plugin OU Mapas Builder

Plugin WordPress que habilita um shortcode para buscar informações da API do **MapasCulturais**, e insere um módulo adicional ao Divi Builder, que permite configurar esse shortcode.

## Shortcodes
### [list_entities] 
Lista entidades de uma instalação do Mapas Culturais

#### Atributos
- **`url`***(obrigatorio)*: 
	URL da instalação do Mapas Culturais.
 
	**Exemplo**: `http://mapa.hackers.org.br/`

- **`entity`**: 
	Tipo da entidade a ser listada. 

	**Padrão**: `event`
   
- **`select`**:
	Campos a serem retornados pela API
    
    **Padrão**: `name,shortDescription`
    
- **`files`**:
	Arquivos a serem retornados pela API
    
    **Padrão**: `header.header,avatar.avatarBig`
    
- **`order`**: 
	Ordenação na qual o resultado será exibido
	
    **Padrão**: `id DESC`
    
- **`limit`**: 
	Limite de resultados retornados pela API
    
    **Padrão**: `10`
    
- **`pagination`**:
	Se a paginação deva ser incluida na renderização do resultado da API
    
    **Padrão**: `false`
    
- **`seals`**:
	Filtra os resultados, mantendo somente os que tiverem os selos especificados.
    
    **Exemplo**: `1,3,25`
    
   - **`profiles`**:
	Filtra os resultados, mantendo somente os que tiverem linkados aos profiles especificados.
    
    **Exemplo**: `1,3,25`
