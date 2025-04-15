<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ТЗ на вакансию Программист PHP (часть вторая) - Laravel</title>
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0;
      padding: 0;
      display: flex;
      font-family: Arial, sans-serif;
      min-height: 100vh;
    }
    #themes, #subthemes {
      width: 20%;
      padding: 10px;
      border-right: 1px solid #ccc;
      background-color: #e3e2e2;
      max-width: 200px;
    }
    #subthemes { background-color: #fafafa; }
    #content { flex: 1; padding: 10px 20px; }
    .item {
      cursor: pointer;
      margin: 5px 0;
      padding: 5px;
      border-radius: 4px;
    }
    .item:hover { background-color: #b3aeae; }
    .active { background-color: #b0c4de; }
    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
    th, td { padding: 8px; border: 1px solid #ccc; text-align: left; }
    th { background-color: #f0f0f0; }
    button { margin: 5px; padding: 5px 10px; }
    .modal-overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }
    .modal-content {
      background: #fff;
      padding: 20px;
      border-radius: 5px;
      width: 400px;
      position: relative;
    }
    .modal-content h3 { margin-top: 0; }
    .close-button {
      position: absolute;
      right: 10px;
      top: 10px;
      cursor: pointer;
      font-size: 20px;
    }
    .modal-form-group { margin-bottom: 10px; }
    .modal-form-group label { display: block; margin-bottom: 5px; }
    .modal-form-group input,
    .modal-form-group select { width: 100%; padding: 5px; }
    /* Адаптивность для мобильных устройств */
    @media (max-width: 768px) {
      body { flex-direction: column; }
      #themes, #subthemes, #content {
        width: 100%;
        border-right: none;
        border-bottom: 1px solid #ccc;
        max-width: 100%;
        padding: 15px 20px;
      }
      #content { border-bottom: none; }
    }
  </style>
</head>
<body>
  <div id="themes"></div>
  <div id="subthemes"></div>
  <div id="content"></div>

  <div id="modalOverlay" class="modal-overlay">
    <div class="modal-content">
      <span class="close-button" onclick="closeModal()">&times;</span>
      <h3 id="modalTitle">Форма</h3>
      <form id="entityForm">
        <div id="modalFields"></div>
        <button type="submit" id="modalSubmitBtn">Сохранить</button>
        <button type="button" onclick="closeModal()">Отмена</button>
      </form>
    </div>
  </div>
  <div id="linkModalOverlay" class="modal-overlay">
    <div class="modal-content">
      <span class="close-button" onclick="closeLinkModal()">&times;</span>
      <h3 id="linkModalTitle"></h3>
      <div class="modal-form-group">
        <label for="linkSelect">Выберите элемент:</label>
        <select id="linkSelect"></select>
      </div>
      <button onclick="confirmLink()">Привязать</button>
      <button onclick="closeLinkModal()">Отмена</button>
    </div>
  </div>

  <script>
    const dealsData = @json($deals);
    const contactsData = @json($contacts);
    const menuItems = [{
        name: "Сделки",
        type: "deals"
      },
      {
        name: "Контакты",
        type: "contacts"
      }
    ];
    const themesContainer = document.getElementById('themes');
    const subthemesContainer = document.getElementById('subthemes');
    const contentContainer = document.getElementById('content');
    let currentMenuType = null; // "deals" или "contacts"
    let currentItem = null; // выбранная Сделка или Контакт
    let modalMode = null; // "create" или "edit"
    let modalType = null; // "deals" или "contacts"
    let modalItem = null;
    let linkMode = null; // "contactToDeal" или "dealToContact"
    let linkCurrentId = null; // id текущей сделки или контакта для связи

    function renderMenuItems() {
      themesContainer.innerHTML = '';
      menuItems.forEach(item => {
        const menuElement = document.createElement('div');
        menuElement.textContent = item.name;
        menuElement.classList.add('item');
        menuElement.addEventListener('click', () => {
          selectMenuType(item.type);
        });
        if (currentMenuType === item.type) menuElement.classList.add('active');
        themesContainer.appendChild(menuElement);
      });
    }
    function renderSubItems() {
      subthemesContainer.innerHTML = '';
      // Кнопка создания нового элемента
      const createBtn = document.createElement('button');
      createBtn.textContent = currentMenuType === 'deals' ? '+ Сделка' : '+ Контакт';
      createBtn.addEventListener('click', () => {
        openModal('create', currentMenuType);
      });
      subthemesContainer.appendChild(createBtn);
      if (currentMenuType === 'deals') {
        dealsData.forEach(deal => {
          const dealElement = document.createElement('div');
          dealElement.textContent = deal.title;
          dealElement.classList.add('item');
          dealElement.addEventListener('click', () => {
            selectItem(deal);
          });
          if (currentItem && currentItem.id === deal.id && currentMenuType === 'deals')
            dealElement.classList.add('active');
          subthemesContainer.appendChild(dealElement);
        });
      } else if (currentMenuType === 'contacts') {
        if (contactsData.length === 0) {
          const emptyDiv = document.createElement('div');
          emptyDiv.textContent = "Нет контактов.";
          subthemesContainer.appendChild(emptyDiv);
          return;
        }
        contactsData.forEach(contact => {
          const contactElement = document.createElement('div');
          contactElement.textContent = `${contact.first_name} ${contact.last_name || ''}`;
          contactElement.classList.add('item');
          contactElement.addEventListener('click', () => {
            selectItem(contact);
          });
          if (currentItem && currentItem.id === contact.id && currentMenuType === 'contacts')
            contactElement.classList.add('active');
          subthemesContainer.appendChild(contactElement);
        });
      }
    }
    // Рендер подробной информации выбранного элемента
    function renderDetails(item) {
      let html = '<table><tr><th>Параметр</th><th>Значение</th></tr>';
      if (currentMenuType === 'deals') {
        html += `<tr><td>ID Сделки</td><td>${item.id}</td></tr>`;
        html += `<tr><td>Наименование</td><td>${item.title}</td></tr>`;
        html += `<tr><td>Сумма</td><td>${item.amount}</td></tr></table>`;
        html += `<button onclick="openModal('edit', 'deals', ${item.id})">Редактировать</button>`;
        html += `<button onclick="deleteItem(${item.id})">Удалить</button>`;
        html += '<h3>Связанные контакты</h3>';
        if (item.contacts && item.contacts.length > 0) {
          html += '<table><tr><th>ID</th><th>ФИО</th></tr>';
          item.contacts.forEach(contact => {
            html += `<tr>
                      <td>${contact.id}</td>
                      <td>${contact.first_name} ${contact.last_name || ''}</td>
                      <td>
                        <button onclick="deleteItem(${contact.pivot.id}, 'contact-deal')">Удалить</button>
                      </td>
                    </tr>`;
          });
          html += `</table><button onclick="openLinkModal('contactToDeal', ${item.id})">Добавить контакт к сделке</button>`;
        } else {
          html += `<p>Нет привязанных контактов</p><button onclick="openLinkModal('contactToDeal', ${item.id})">Добавить контакт к сделке</button>`;
        }
      } else if (currentMenuType === 'contacts') {
        html += `<tr><td>ID Контакта</td><td>${item.id}</td></tr>`;
        html += `<tr><td>Имя</td><td>${item.first_name}</td></tr>`;
        html += `<tr><td>Фамилия</td><td>${item.last_name || ''}</td></tr></table>`;
        html += `<button onclick="openModal('edit', 'contacts', ${item.id})">Редактировать</button>`;
        html += `<button onclick="deleteItem(${item.id})">Удалить</button>`;
        html += '<h3>Связанные сделки</h3>';
        if (item.deals.length > 0) {
          html += '<table><tr><th>ID</th><th>Наименование</th><th>Сумма</th></tr>';
          item.deals.forEach(deal => {
            html += `<tr><td>${deal.id}</td><td>${deal.title}</td><td>${deal.amount}</td>
                    <td><button onclick="deleteItem(${deal.pivot.id}, 'contact-deal')">Удалить</button></td>
                    </tr>`;
          });
          html += '</table>';
        } else {
          html += '<p>Сделок не найдено</p>';
        }
        html += `<button onclick="openLinkModal('dealToContact', ${item.id})">Добавить сделку к контакту</button>`;
      }
      contentContainer.innerHTML = html;
    }
    function selectMenuType(type) {
      currentMenuType = type;
      currentItem = null;
      renderMenuItems();
      renderSubItems();
      contentContainer.innerHTML = "";
    }
    function selectItem(item) {
      currentItem = item;
      renderSubItems();
      renderDetails(item);
    }
    /*** Функции для создания/редактирования через модальное окно ***/
    function openModal(mode, type, itemId = null) {
      modalMode = mode;
      modalType = type;
      if (mode === 'edit' && itemId) {
        if (type === 'deals') {
          modalItem = dealsData.find(deal => deal.id === itemId);
        } else if (type === 'contacts') {
          modalItem = contactsData.find(contact => contact.id === itemId);
        }
      } else {
        modalItem = null;
      }
      const modalTitle = document.getElementById('modalTitle');
      const modalFields = document.getElementById('modalFields');
      modalFields.innerHTML = '';
      if (type === 'deals') {
        modalTitle.textContent = mode === 'create' ? 'Создание сделки' : 'Редактирование сделки';
        modalFields.innerHTML += `
          <div class="modal-form-group">
            <label for="dealTitle">Наименование*</label>
            <input type="text" id="dealTitle" name="title" value="${ modalItem ? modalItem.title : '' }" required>
          </div>
          <div class="modal-form-group">
            <label for="dealAmount">Сумма</label>
            <input type="text" id="dealAmount" name="amount" value="${ modalItem ? modalItem.amount : '0.00' }">
          </div>
        `;
      } else if (type === 'contacts') {
        modalTitle.textContent = mode === 'create' ? 'Создание контакта' : 'Редактирование контакта';
        modalFields.innerHTML += `
          <div class="modal-form-group">
            <label for="contactFirstName">Имя*</label>
            <input type="text" id="contactFirstName" name="first_name" value="${ modalItem ? modalItem.first_name : '' }" required>
          </div>
          <div class="modal-form-group">
            <label for="contactLastName">Фамилия</label>
            <input type="text" id="contactLastName" name="last_name" value="${ modalItem ? modalItem.last_name || '' : '' }">
          </div>
        `;
      }
      document.getElementById('modalOverlay').style.display = 'flex';
    }
    document.getElementById('entityForm').addEventListener('submit', function(event) {
      event.preventDefault();
      let data = {};
      let url = '';
      let method = '';
      if (modalType === 'deals') {
        const title = document.getElementById('dealTitle').value.trim();
        if (!title) {
          alert("Наименование обязательно!");
          return;
        }
        data.title = title;
        data.amount = document.getElementById('dealAmount').value.trim();
      } else if (modalType === 'contacts') {
        const first_name = document.getElementById('contactFirstName').value.trim();
        if (!first_name) {
          alert("Имя обязательно!");
          return;
        }
        data.first_name = first_name;
        data.last_name = document.getElementById('contactLastName').value.trim();
      }
      if (modalMode === 'create') {
        url = `/${modalType}`;
        method = 'POST';
      } else if (modalMode === 'edit' && modalItem) {
        url = `/${modalType}/${modalItem.id}`;
        method = 'PUT';
      }
      fetch(url, {
          method: method,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
          const itemParam = currentItem ? '&item=' + currentItem.id : '';
          window.location.href = window.location.pathname + '?menu=' + currentMenuType + itemParam;
        })
        .catch(err => console.error(err));
    });
    function deleteItem(id, type) {
      if (!confirm("Вы уверены, что хотите удалить элемент?")) return;
      type = type ? type : currentMenuType;
      fetch(`/${type}/${id}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
          })
          .then(response => {
            if (response.ok && type == 'contact-deal') {
              const itemParam = currentItem ? '&item=' + currentItem.id : '';
              window.location.href = window.location.pathname + '?menu=' + currentMenuType + itemParam;
            } 
            else {
              window.location.href = window.location.pathname + '?menu=' + currentMenuType;
            }
          })
          .catch(err => console.error(err));
    }
    function openLinkModal(linkType, currentId) {
      linkMode = linkType;
      linkCurrentId = currentId;
      const linkModalTitle = document.getElementById('linkModalTitle');
      const linkSelect = document.getElementById('linkSelect');
      linkSelect.innerHTML = '';
      let options = [];
      if (linkType === 'contactToDeal') {
        // Для выбранной сделки — показываем все контакты из contactsData, исключая уже привязанные
        const currentDeal = dealsData.find(deal => deal.id === currentId);
        let linkedContacts = currentDeal && currentDeal.contacts ? currentDeal.contacts.map(c => c.id) : [];
        options = contactsData.filter(contact => !linkedContacts.includes(contact.id));
        linkModalTitle.textContent = 'Выберите контакт для привязки к сделке';
      } else if (linkType === 'dealToContact') {
        // Для выбранного контакта – показываем сделки из dealsData, где контакт ещё не привязан
        options = dealsData.filter(deal => !deal.contacts.some(c => c.id === currentId));
        linkModalTitle.textContent = 'Выберите сделку для привязки к контакту';
      }
      if (options.length === 0) {
        alert("Нет доступных элементов для привязки.");
        return;
      }
      options.forEach(option => {
        const opt = document.createElement('option');
        opt.value = option.id;
        if (linkMode === 'contactToDeal')
          opt.textContent = `${option.first_name} ${option.last_name || ''} (ID: ${option.id})`;
        else if (linkMode === 'dealToContact')
          opt.textContent = `${option.title} (ID: ${option.id})`;
        linkSelect.appendChild(opt);
      });
      document.getElementById('linkModalOverlay').style.display = 'flex';
    }
    function confirmLink() {
      const linkSelect = document.getElementById('linkSelect');
      const selectedId = linkSelect.value;
      if (!selectedId) {
        alert("Выберите элемент!");
        return;
      }
      let payload = {};
      if (linkMode === 'contactToDeal') {
        payload = {
          deal_id: linkCurrentId,
          contact_id: selectedId
        };
      } else if (linkMode === 'dealToContact') {
        payload = {
          deal_id: selectedId,
          contact_id: linkCurrentId
        };
      }
      fetch(`/contact-deal`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
          alert("Элемент успешно привязан.");
          closeLinkModal();
          const itemParam = currentItem ? '&item=' + currentItem.id : '';
          window.location.href = window.location.pathname + '?menu=' + currentMenuType + itemParam;
        })
        .catch(err => console.error(err));
    }
    function closeModal() {
      document.getElementById('modalOverlay').style.display = 'none';
    }
    function closeLinkModal() {
      document.getElementById('linkModalOverlay').style.display = 'none';
    }

    // Инициализация
    window.addEventListener('DOMContentLoaded', () => {
      const urlParams = new URLSearchParams(window.location.search);
      currentMenuType = urlParams.get('menu') || 'deals';
      const selectedItemId = urlParams.get('item') ? parseInt(urlParams.get('item')) : null;
      renderMenuItems();
      renderSubItems();
      if (currentMenuType === 'deals' && dealsData.length > 0) {
        if (selectedItemId) {
          currentItem = dealsData.find(deal => deal.id === selectedItemId) || dealsData[0];
        } else {
          currentItem = dealsData[0];
        }
      } else if (currentMenuType === 'contacts' && contactsData.length > 0) {
        if (selectedItemId) {
          currentItem = contactsData.find(contact => contact.id === selectedItemId) || contactsData[0];
        } else {
          currentItem = contactsData[0];
        }
      }
      if (currentItem) {
        renderDetails(currentItem);
      }
    });
  </script>
</body>
</html>
